@if(count($recent) == 0)
    {{ __('No data') }}.
@else
    <div class="list-group list-group-flush my-n3">
        @foreach($recent as $pageview)
            <div class="list-group-item px-0">
                <div class="row align-items-center">
                    <div class="col text-truncate">
                        <div class="row align-items-center">
                            <div class="col-6 col-lg-3 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('/images/icons/countries/' . formatFlag($pageview->country)) }}.svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">

                                    <div class="text-truncate">
                                        @if(!empty(explode(':', $pageview->country)[1]))
                                            {{ explode(':', $pageview->country)[1] }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                    <div class="text-muted text-truncate small">
                                        @if(!empty(explode(':', $pageview->city)[1]))
                                            {{ explode(':', $pageview->city)[1] }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('/images/icons/browsers/'.formatBrowser($pageview->browser)) }}.svg" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">

                                    <div class="text-truncate">
                                        @if($pageview->browser)
                                            {{ $pageview->browser }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                    <div class="text-muted text-truncate small">
                                        @if($pageview->os)
                                            {{ $pageview->os }}
                                        @else
                                            {{ __('Unknown') }}
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    @if($pageview->referrer)
                                        <img src="https://icons.duckduckgo.com/ip3/{{ $pageview->referrer }}.ico" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    @else
                                        <img src="{{ asset('/images/icons/referrers/unknown.svg') }}" rel="noreferrer" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">
                                    @endif

                                    <div class="text-truncate">
                                        @if($pageview->referrer)
                                            <div class="text-truncate">{{ $pageview->referrer }}</div>
                                        @else
                                            {{ __('Direct, Email, SMS') }}
                                        @endif
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                    <div class="text-muted text-truncate small d-flex align-items-center">
                                        <div class="text-truncate">{{ $website->url . $pageview->page }}</div> <a href="http://{{ $website->url . $pageview->page }}" target="_blank" rel="nofollow noreferrer noopener" class="text-secondary d-flex align-items-center {{ (__('lang_dir') == 'rtl' ? 'mr-2' : 'ml-2') }}">@include('icons.open-new', ['class' => 'fill-current width-3 height-3'])</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6 col-lg-3 mb-2 mb-lg-0">
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('/images/icons/time.svg') }}" class="width-4 height-4 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}">

                                    <div class="text-truncate">
                                        {{ $pageview->created_at->diffForHumans(['options' => \Carbon\Carbon::JUST_NOW]) }}
                                    </div>
                                </div>

                                <div class="d-flex align-items-center">
                                    <div class="width-4 flex-shrink-0 {{ (__('lang_dir') == 'rtl' ? 'ml-2' : 'mr-2') }}"></div>
                                    <div class="text-muted text-truncate small">
                                        {{ $pageview->created_at->format(__('Y-m-d')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif