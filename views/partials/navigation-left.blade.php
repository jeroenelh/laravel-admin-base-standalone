<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <section class="sidebar">
        {{--@include('laravel-admin-base-standalone::partials.navigation.left-userinfo')--}}
        {{--@include('laravel-admin-base::partials.navigation.left-search')--}}

        <?php
        function renderNavigation($nav) {
            $return = "<ul class='sidebar-menu'>\n";
            $return.= "    <li class='header'>Navigatie</li>\n";

            $return.= renderNavigationItems($nav);
            $return.= "</ul>";

            return $return;
        }

        function renderNavigationItems($navigation = []) {
            if (count($navigation) == 0) {
                return;
            }
            $active_route = explode("/",parse_url(\Request::url(), PHP_URL_PATH));
            $return = "";
            foreach($navigation as $category) {
                if (isset($category['permission']) && !\Illuminate\Support\Facades\Auth::user()->may($category['permission'])) {
                    continue;
                }

                $return.= "  <li>\n";
                $class = "";
                if (isset($category['route'])) {
                    // Check if the current url is the active url
                    $url = route($category['route'], [], false);
                    $url_explode = explode("/",$url);
                    $url_match = true;
                    foreach ($url_explode as $index => $url_piece) {
                        if (isset($active_route[$index]) && $active_route[$index] != $url_piece) {
                            $url_match = false;
                        }
                    }
                    if ($url_match) {
                        $class = "active_link";
                    }
                } else {
                    $url = "#";
                }
                $return.= "      <a href='".$url."' class='".$class."'>\n";
                if (!isset($category['icon'])) {
                    $category['icon'] = "fa-circle-o";
                }
                $return.= "      <i class='fa ".$category['icon']."'></i>\n";
                $return.= "          <span>".$category['display_name']."</span>\n";
                if (isset($category['icon-right']) || (isset($category['children']) && count($category['children']) > 0 && !isset($category['icon-right']) && $category['icon-right'] = 'fa-angle-left')) {
                    $return.= "     <span class='pull-right-container'>";
                    $return.= "      <i class='fa ".$category['icon-right']." pull-right'></i>\n";
                    $return.= "     </span>";
                }
                $return.= "      </a>\n";

                // Children
                if (isset($category['children'])) {
                    $return.= "<ul class='treeview-menu'>\n";
                    $return.= renderNavigationItems($category['children']);
                    $return.= "</ul>\n";
                }
                $return.= "  </li>\n";
            }

            return $return;
        }

        $files = [];
        if (file_exists(base_path('navigation.yaml'))) {
            $files[] = base_path('navigation.yaml');
        }
        foreach (glob(base_path('vendor/microit')."/*/navigation.yaml") as $file) {
            $files[] = $file;
        }
        $navigation = [];
        foreach ($files as $file) {
            $yaml = \Symfony\Component\Yaml\Yaml::parse(file_get_contents($file));

            if ($yaml && is_array($yaml) && count($yaml) > 0) {
                foreach ($yaml as $cat => $catInfo) {
                    if (!isset($navigation[$cat])) {
                        $navigation[$cat] = $catInfo;
                    } else {
                        if (isset($catInfo['children']) && count($catInfo['children'])>0) {
                            foreach ($catInfo['children'] as $childName => $childInfo) {
                                $navigation[$cat]['children'][$childName] = $childInfo;
                            }
                        }
                    }
                }
            }
        }
        
        echo renderNavigation($navigation);
        ?>
    </section>

    <?php
    foreach (glob(base_path('vendor/microit')."/*/sidebar.blade.php") as $file) {
        ?>
        <section style="padding: 10px;">
        <?php include($file); ?>
        </section>
        <?php
    }
    ?>
</aside>

@section('scripts_footer')
<script>
    if ($(".main-sidebar .active_link").length) {
        var link = $(".main-sidebar .active_link");
        link.parents('li').addClass('active');
    }
</script>
@stop