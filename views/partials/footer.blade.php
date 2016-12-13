<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <?php
        $version = trim(shell_exec("git describe --abbrev=0 --tags"));
        $hash = trim(shell_exec("git rev-parse --short HEAD"));
        $time = date('d-m-Y H:i:s', strtotime(shell_exec("git log -1 --pretty=format:'%ci'")));
        ?>
        <span title="Laatste update: {{ $time }}" data-toggle="modal" data-target="#footer_modules"><b>Versie:</b> {{ $version }}-{{ $hash }}</span>
    </div>
    <strong>
        Copyright &copy;
        @if(config('laravel-admin-base-standalone.copyright_year', date('Y')) < date('Y'))
            {{ config('laravel-admin-base-standalone.copyright_year') }}-{{ date('Y') }}
        @else
            {{ date('Y') }}
        @endif
        <a href="{{ config('laravel-admin-base-standalone.copyright_link', 'http://micro-it.nl/') }}">
            {{ config('laravel-admin-base-standalone.copyright_name', 'Micro-IT') }}
        </a>
    </strong>
</footer>
<div class="modal fade" id="footer_modules" tabindex="-1" role="dialog" aria-labelledby="footer_modules_label" aria-hidden="true" style="z-index:1100">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="footer_modules_label">Module overzicht</h4>
            </div>
            <div class="modal-body">
                <?php
                $modules = [];
                $files = glob(base_path().'/vendor/microit/*/composer.json');
                foreach ($files as $file) {
                    $json = json_decode(file_get_contents($file));
                    $folder = substr($file, 0, -strlen("/composer.json"));

                    $modules[] = [
                        'title' => $json->name,
                        'authors' => isset($json->authors) ? $json->authors : [],
                        'version' => trim(shell_exec("git -C ".$folder." describe --abbrev=0 --tags")) ? : 'Onbekend',
                        'hash' => trim(shell_exec("git -C ".$folder." rev-parse --short HEAD")) ? : 'Onbekend',
                        'last_update' => date('d-m-Y H:i:s', strtotime(shell_exec("git -C ".$folder." log -1 --pretty=format:'%ci'"))),
                    ];
                }
                ?>
                <ul class="products-list product-list-in-box">
                    @foreach($modules as $module)
                        <li class="item">
                            <dl class="dl-horizontal no-margin">
                                <dt>Module:</dt>
                                <dd>{{ $module['title'] }}</dd>

                                @if(count($module['authors']))
                                <dt>Autheurs:</dt>
                                <dd>
                                    @foreach($module['authors'] as $author)
                                        @if(isset($author->name))
                                            {{ $author->name }}
                                        @endif
                                        @if(isset($author->email))
                                            <a href="mailto:{{ $author->email }}"><i class="fa fa-envelope"></i></a>
                                        @endif
                                        @if(isset($author->homepage))
                                            <a href="{{ $author->homepage }}" target="_blank"><i class="fa fa-home"></i></a>
                                        @endif
                                        <br />
                                    @endforeach
                                </dd>
                                @endif

                                <dt>Versie:</dt>
                                <dd>{{ $module['version'] }}-{{ $module['hash'] }} / {{ $module['last_update'] }}</dd>
                            </dl>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>