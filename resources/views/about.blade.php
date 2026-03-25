@extends('layouts.app')

@section('content')
    <style>
        .clip-diagonal {
            clip-path: polygon(0 0, 100% 0, 100% 85%, 0 100%);
        }

        .clip-card {
            clip-path: polygon(20px 0, 100% 0, 100% calc(100% - 20px), calc(100% - 20px) 100%, 0 100%, 0 20px);
        }

        .text-outline {
            -webkit-text-stroke: 1px rgba(255, 255, 255, 0.1);
            color: transparent;
        }

        .scanline {
            width: 100%;
            height: 100px;
            z-index: 10;
            background: linear-gradient(0deg, rgba(0, 0, 0, 0) 0%, rgba(59, 130, 246, 0.1) 50%, rgba(0, 0, 0, 0) 100%);
            opacity: 0.1;
            position: absolute;
            bottom: 100%;
            animation: scanline 8s linear infinite;
            pointer-events: none;
        }

        @keyframes scanline {
            0% {
                bottom: 100%;
            }

            100% {
                bottom: -100%;
            }
        }
    </style>

    <div class="bg-[#050505] min-h-screen text-white overflow-hidden font-sans selection:bg-blue-500 selection:text-white">

        @include('sections.about.hero')

        @include('sections.about.stats')

        @include('sections.about.story')

        @include('sections.about.process')

        @include('sections.about.partners')

        @include('sections.home.cta')

    </div>
@endsection
