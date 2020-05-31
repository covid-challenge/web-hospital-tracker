<aside class="ct-sidebar">
        <div class="ct-sidebar__brand">
            <a href="login.php">
                <!-- <img src="" alt=""> -->
            </a>
        </div>

        <div class="ct-sidebar__content">
            <div class="ct-activity__search_wrapper scrollFixed">
                <div class="ct-activity__search_group">
                    <span class="search__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </span>
                    <input type="hidden" class="token" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" class="nearest_hospital" name="url" value="{{url('nearest-hospital')}}">
                    <input class="ct-activity__search_input" data-url="{{url('search-hospital')}}" value="" type="text" name="search" placeholder="Search your hospital...">
                </div>
            </div>
            <div class="p-3">
                <button class="btn btn-danger density-toggle-off" onclick="disablePopulationDensityMap()">Hide Population Density</button>
                <button class="btn btn-primary density-toggle-on" onclick="enablePopulationDensityMap()">Show Population Density</button>
            </div>
            <div class="header_default">
              <h1 class="mt-2">HospitALL</h1>
              <p>
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
              </p>
            </div>
            <div class="container_header" style="display:none !important;">
              <h1 class="mt-2 search_header">Search results</h1>
               <div class="ct-activity__container wt-container">

               </div>
           </div>
        </div>

        <p class="copyright">&copy; HospitALL. All Rights Reserved 2020.</p>
    </aside>