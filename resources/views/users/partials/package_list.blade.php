<div class="row g-4 mb-50">
    <div class="package-inner-title-section">
        @if ($packages->total() > 0)
            <p>Showing {{ $packages->firstItem() }} – {{ $packages->lastItem() }} of {{ $packages->total() }} results</p>
        @else
            <p>No results found.</p>
        @endif
        <div class="selector-and-grid">
            <div class="selector">
               <form id="filterForm" method="GET" action="{{ route('user.package.filter') }}">
                {{-- Sort dropdown --}}
                <select name="sort" id="sortBy">
                    <option value="">Sort by...</option>
                    <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>Rating: High to Low</option>
                    <option value="rating_asc" {{ request('sort') == 'rating_asc' ? 'selected' : '' }}>Rating: Low to High</option>
                    <option value="duration_desc" {{ request('sort') == 'duration_desc' ? 'selected' : '' }}>Duration: High to Low</option>
                    <option value="duration_asc" {{ request('sort') == 'duration_asc' ? 'selected' : '' }}>Duration: Low to High</option>
                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                </select>

                {{-- Preserve filters from other forms --}}
                @foreach (request()->except('sort', 'page') as $key => $value)
                    @if (is_array($value))
                        @foreach ($value as $subVal)
                            <input type="hidden" name="{{ $key }}[]" value="{{ $subVal }}">
                        @endforeach
                    @else
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
            </form>
            </div>
        </div>
    </div>
    @foreach ($packages as $package)
    <div class="col-md-6  wow animate fadeInUp" data-wow-delay="{{ ($loop->index + 1) * 200 }}ms" data-wow-duration="1500ms">
        <div class="package-card">
            <div class="package-card-img-wrap">
                <a href="{{route('user.package.show',$package->id)}}" class="card-img"><img
                        src="{{ asset('storage/' . $package->image) }}" alt="" /></a>
                <div class="batch"><span class="featured">{{$package->tag?->tag_name}}</span>
                </div>
                <div class="review">
                    <div class="icon">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M6.52447 1.46352C6.67415 1.00287 7.32585 1.00287 7.47553 1.46353L8.45934 4.49139C8.52628 4.6974 8.71826 4.83688 8.93487 4.83688H12.1186C12.6029 4.83688 12.8043 5.45669 12.4124 5.74139L9.83679 7.61271C9.66155 7.74003 9.58822 7.96572 9.65516 8.17173L10.639 11.1996C10.7886 11.6602 10.2614 12.0433 9.86955 11.7586L7.29389 9.88729C7.11865 9.75997 6.88135 9.75997 6.70611 9.88729L4.13045 11.7586C3.73859 12.0433 3.21136 11.6602 3.36103 11.1996L4.34484 8.17173C4.41178 7.96572 4.33845 7.74003 4.16321 7.61271L1.58755 5.74139C1.1957 5.45669 1.39708 4.83688 1.88145 4.83688H5.06513C5.28174 4.83688 5.47372 4.6974 5.54066 4.49139L6.52447 1.46352Z"
                                fill="#F38035" />
                        </svg>
                    </div>
                    <span>{{ $package->average_rating }}</span>
                </div>
            </div>
            <div class="package-card-content">
                <div class="card-content-top">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="">
                            <h5>
                                <a href="{{route('user.package.show',$package->id)}}">{{$package->package_name}}</a>
                            </h5>
                            <span class="location">{{$package->country?->country_name}} </span>
                        </div>
                        <span class="days_night_block">{{$package->duration  + 1 }} Days / {{$package->duration}} Nights</span>
                    </div>
                    <div class="colon_trip d-flex align-items-start justify-content-between px-3">
                        @php
                            $inclusions = $package->inclusions;
                            $chunks = array_chunk($inclusions, 4);
                        @endphp

                        @foreach ($chunks as $chunk)
                            <ul class="d-block mb-0">
                                @foreach ($chunk as $inclusion)
                                    <li class="d-block">{{ $inclusion }}</li>
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
                <div class="card-content-bottom row flex-nowrap">
                    <p class="col-6">This price is lower than the average price in August</p>
                    <div class="price-area col-6">
                        <h5 class="mb-0">AED {{ number_format($package->starting_price , 2)}}<span>/  {{$package->type?->type_name}}</span></h5>
                        {{-- <h6>Total Price $1,17,542</h6> --}}
                    </div>
                    <!-- <a href="#" class="primary-btn small">Book now </a> -->
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
<div class="row">
    <div class="col-md-12">
        <nav class="inner-pagination-area">
            @if ($packages->total() > 0)
            <ul class="pagination-list">
                {{-- Previous --}}
                @if ($packages->onFirstPage()) 
                    <li><span class="disabled"><b class="shop-pagi-btn"><i class="bi bi-chevron-left"></i></b></span></li>
                @else
                    @php
                        $prevUrl = $packages->previousPageUrl() . '&' . http_build_query(request()->except('page'));
                    @endphp
                    <li><a href="{{ $prevUrl }}"><b class="shop-pagi-btn"><i class="bi bi-chevron-left"></i></b></a></li>
                @endif



                {{-- Page Numbers --}}
                @foreach ($packages->getUrlRange(1, $packages->lastPage()) as $page => $url)
                    @php
                        $fullUrl = $url . '&' . http_build_query(request()->except('page'));
                    @endphp
                    <li>
                        <a href="{{ $fullUrl }}" class="{{ $page == $packages->currentPage() ? 'active' : '' }}">
                            {{ str_pad($page, 2, '0', STR_PAD_LEFT) }}
                        </a>
                    </li>
                @endforeach

                {{-- Next --}}
                @if ($packages->hasMorePages())
                    @php
                        $nextUrl = $packages->nextPageUrl() . '&' . http_build_query(request()->except('page'));
                    @endphp
                    <li><a href="{{ $nextUrl }}"><b class="shop-pagi-btn"><i class="bi bi-chevron-right"></i></b></a></li>
                @endif

            </ul>
            @endif
        </nav>
    </div>
</div>