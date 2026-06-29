@extends('layouts.app')

@section('title', 'Preview Sertifikat — ' . $sertifikat->nomor_sertifikat)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('mentor.sertifikat.index') }}"
                    class="flex items-center gap-1 text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition-colors">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Kembali
                </a>
                <span class="text-gray-300 dark:text-gray-700">/</span>
                <h1 class="text-xl font-bold text-gray-900 dark:text-white">Preview Sertifikat</h1>
            </div>
            <a href="{{ route('mentor.sertifikat.edit', $sertifikat) }}"
                class="inline-flex items-center gap-2 rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors">
                Edit
            </a>
        </div>

        {{-- Certificate Preview Card (Edugenzi Style) --}}
        <div class="mx-auto max-w-3xl">
            <div class="relative overflow-hidden rounded-2xl bg-white shadow-2xl" style="min-height: 420px;">

                {{-- Deco: Orange big circle bottom-right --}}
                <div class="absolute rounded-full"
                    style="bottom:-80px;right:-60px;width:260px;height:260px;background:#F4930B;z-index:0;"></div>

                {{-- Deco: Green + Orange diamonds top-right --}}
                <div class="absolute" style="top:0;right:0;width:150px;height:150px;z-index:0;">
                    <div class="absolute"
                        style="width:42px;height:42px;background:#3BA935;border-radius:6px;transform:rotate(45deg);top:8px;right:48px;">
                    </div>
                    <div class="absolute"
                        style="width:42px;height:42px;background:#3BA935;border-radius:6px;transform:rotate(45deg);top:8px;right:4px;opacity:0.65;">
                    </div>
                    <div class="absolute"
                        style="width:42px;height:42px;background:#F4930B;border-radius:6px;transform:rotate(45deg);top:56px;right:26px;">
                    </div>
                    <div class="absolute"
                        style="width:42px;height:42px;background:#F4930B;border-radius:6px;transform:rotate(45deg);top:56px;right:72px;opacity:0.45;">
                    </div>
                </div>

                {{-- Deco: Small green diamonds bottom-left --}}
                <div class="absolute flex gap-2" style="bottom:12px;left:-14px;z-index:1;">
                    <div style="width:30px;height:30px;background:#3BA935;border-radius:4px;transform:rotate(45deg);"></div>
                    <div
                        style="width:30px;height:30px;background:#F4930B;border-radius:4px;transform:rotate(45deg);opacity:0.7;">
                    </div>
                    <div
                        style="width:30px;height:30px;background:#3BA935;border-radius:4px;transform:rotate(45deg);opacity:0.4;">
                    </div>
                </div>

                {{-- Laptop Image --}}
                <img src="{{ asset('images/cert-laptop.png') }}" alt="" class="absolute"
                    style="top:10px;right:160px;width:130px;z-index:1;">

                {{-- Content --}}
                <div class="relative z-10 p-10">

                    {{-- Logo --}}
                    <div class="mb-5">
                        <img src="{{ asset('images/logo/logo.svg') }}" alt="Edugenzi" class="h-9">
                    </div>

                    {{-- Title --}}
                    <p class="text-xl font-black tracking-wide text-gray-900" style="font-family:'Inter',sans-serif;">
                        CERTIFICATE OF <span style="color:#3BA935;">COMPLETION</span>
                    </p>
                    <p class="text-xs text-gray-500 mt-1 mb-2">This certificate is proudly presented to</p>

                    {{-- Name --}}
                    <h2 class="font-black text-4xl mb-3"
                        style="color:#3BA935;font-family:'Inter',sans-serif;line-height:1.15;">
                        {{ $sertifikat->peserta->nama_lengkap }}
                    </h2>

                    <hr style="border-top:1.5px solid #222;max-width:480px;margin-bottom:12px;">

                    {{-- Description --}}
                    <p class="text-xs text-gray-600 mb-3" style="max-width:420px;line-height:1.7;">
                        for successfully completing the <strong>{{ $sertifikat->nama_program }}</strong>
                        program and fulfilling all learning requirements at Edugenzi.
                    </p>

                    {{-- Meta --}}
                    <p class="text-xs text-gray-600 mb-3" style="line-height:1.8;">
                        Certificate Number: <strong>{{ $sertifikat->nomor_sertifikat }}</strong><br>
                        Student ID (NIS): <strong>{{ $sertifikat->peserta->no_registrasi }}</strong>
                    </p>

                    {{-- Appreciation --}}
                    <p class="text-xs text-gray-500 mb-5" style="max-width:480px;">
                        We appreciate your hard work and wish you continued success in your future learning and
                        achievements.
                    </p>

                    {{-- Footer --}}
                    <div class="flex items-end justify-between" style="max-width:500px;">

                        {{-- QR --}}
                        <div>

                        </div>

                        {{-- Date & Signature --}}
                        <div class="text-center">
                            <p class="text-xs text-gray-600 mb-6">
                                On the day of, {{ $sertifikat->tgl_terbit->translatedFormat('F j, Y') }}
                            </p>
                            <p class="text-sm font-bold text-gray-900 border-t border-gray-800 pt-1.5 min-w-40 mt-15">
                                Semoga Raharja Wijaya
                            </p>
                            <p class="text-xs italic text-gray-500">Founder &amp; CEO Edugenzi Academy</p>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection