<?php

namespace App\Filament\Resources\Categories\Tables;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\ProductSeries;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('series_count')
                    ->label('Series')
                    ->counts('series')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),

                // Tombol disable semua series + produk dalam kategori
                Action::make('disableAll')
                    ->label('Disable All')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Disable Semua Series & Produk')
                    ->modalDescription('Semua series dan produk dalam kategori ini akan dinonaktifkan. Kategori dapat dihapus setelah ini.')
                    ->modalSubmitActionLabel('Ya, Disable Semua')
                    ->action(function ($record) {
                        $record->disableAll();

                        Notification::make()
                            ->title('Berhasil dinonaktifkan')
                            ->body("Semua series dan produk dalam kategori \"{$record->name}\" telah dinonaktifkan.")
                            ->success()
                            ->send();
                        
                           
                    })

                    ->successRedirectUrl(fn () => request()->header('Referer')),

                // Tombol delete dengan notifikasi friendly
               DeleteAction::make()
                    ->before(function ($record, $action) {
                        $seriesIds      = \App\Models\ProductSeries::where('category_id', $record->id)->pluck('id');
                        $activeSeries   = \App\Models\ProductSeries::where('category_id', $record->id)->where('is_active', true)->count();
                        $activeProducts = \App\Models\Product::whereIn('product_series_id', $seriesIds)->where('is_active', true)->count();

                        if ($activeSeries > 0 || $activeProducts > 0) {
                            Notification::make()
                                ->title('Kategori tidak dapat dihapus')
                                ->body("Kategori \"{$record->name}\" masih memiliki {$activeSeries} series dan {$activeProducts} produk yang aktif. Gunakan tombol \"Disable All\" terlebih dahulu.")
                                ->danger()
                                ->persistent()
                                ->send();

                            $action->cancel();
                            return;
                        }

                        // Hapus order_items dan produk dulu sebelum hapus kategori
                        $productIds = \App\Models\Product::whereIn('product_series_id', $seriesIds)->pluck('id');
                        
                        DB::table('order_items')->whereIn('product_id', $productIds)->delete();
                        Product::whereIn('product_series_id', $seriesIds)->delete();
                        ProductSeries::where('category_id', $record->id)->delete();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($records, $action) {
                            foreach ($records as $record) {
                                if ($record->series()->exists()) {
                                    Notification::make()
                                        ->title('Beberapa kategori tidak dapat dihapus')
                                        ->body("Kategori \"{$record->name}\" masih memiliki series dan produk aktif. Nonaktifkan terlebih dahulu.")
                                        ->danger()
                                        ->persistent()
                                        ->send();

                                    $action->cancel();
                                    return;
                                }
                            }
                        }),
                ]),
            ]);
    }
}