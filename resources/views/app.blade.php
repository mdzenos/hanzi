@extends('layouts.main')
@section('title', 'Trang chủ')

@section('content')
    <div class="row card-body">

        <div class="col-12 mb-4">

            <div class="card border-bottom-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">
                            Mẹo
                        </div>
                    </div>
                    <div class="row">
                        @php
                            $randomIndex = rand(1, count($wordTip)) - 1;
                        @endphp

                        <div class="col card-item">
                            {{ $wordTip[$randomIndex]['docs'] ?? 'Không có dữ liệu' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-bottom-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-3">Từ đồng âm:
                            {{ $wordSearch[0]['pinyin'] }}
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($soundHomonym as $sound)
                            <div class="col-4">
                                <div class="card-item">
                                    <a href="{{ route('search', ['word' => $sound['hanzi']]) }}"
                                        style="color:red; font-size:20px;">{{ $sound['hanzi'] }}</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-bottom-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-3">Từ vựng</div>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div style="color:red; font-size:20px;">{{ $wordSearch[0]['hanzi'] }}&nbsp;</div>
                        <div style="font-size:20px;">[{{ $wordSearch[0]['pinyin'] }}]&nbsp;</div>
                        <button onclick="playAudio({{ $wordSearch[0]['id_hanzi'] }})" style="background: none; border:none">
                            <i class="fa fa-volume-up"></i>
                        </button>
                    </div>
                    <div class="row justify-content-center align-items-center">
                        <div class="col ml-2">
                            @foreach ($wordSearch[0]['mean'] as $type => $mean)
                                {{ $type }}: <br />
                                @foreach ($mean as $is)
                                    <div class="col ml-2">
                                        ◦ {{ $is }} <br />
                                    </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card border-bottom-info shadow h-100 py-2">
                <div class="card-body">

                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Thanh điệu khác
                        </div>
                    </div>
                    <div class="row">
                        @foreach ($soundRelated as $sound)
                            <div class="col-4">
                                <div class="card-item">
                                    <a href="{{ route('search', ['word' => $sound['hanzi']]) }}"
                                        style="color:red; font-size:20px;">{{ $sound['hanzi'] }}</a>&nbsp;[{{ $sound['pinyin'] }}]
                                    <button onclick="playAudio({{ $sound['id_hanzi'] }})"
                                        style="background: none; border:none">
                                        <i class="fa fa-volume-up"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        @foreach ($wordRelated as $word)
            <div class="col-md-3
                                    mb-3">
                <div class="card shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row justify-content-center align-items-center">
                            <a href="{{ route('search', ['word' => $word['hanzi']]) }}">
                                <div style="color:red; font-size:20px;">{{ $word['hanzi'] }}</div>
                            </a>
                            <div style="font-size:20px;">
                                &nbsp;[{{ $word['pinyin'] }}]&nbsp;</div>
                            <button onclick="playAudio({{ $word['id_hanzi'] }})" style="background: none; border:none">
                                <i class="fa fa-volume-up"></i>
                            </button>
                        </div>
                        <div class="row justify-content-center align-items-center">
                            <div class="col ml-2">
                                @foreach ($word['mean'] as $type => $mean)
                                    {{ $type }}: <br />
                                    @foreach ($mean as $is)
                                        <div class="col ml-2">
                                            ◦ {{ $is }} <br />
                                        </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection

@push('scripts')
    <script>
        function playAudio(audioId) {
            const audioUrl = `{{ asset('storage/audios/') }}/${audioId}.mp3`;
            const audio = new Audio(audioUrl);
            audio.play();
        }
        $(document).keydown(function(event) {
            if (event.code === 'Space') {
                event.preventDefault();
                window.location.href = "{{ route('search') }}";
            }
        });
    </script>
@endpush
