<div class="row">
    <div class="col-md-3">
        <div class="box">
            <div class="box-header">
                <h2 class="box-title">Lightspeed ratelimit ({{ date("H:i") }})</h2>
            </div>
            <div class="box-body no-padding">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Limit</th>
                        <th>Calls</th>
                        <th>Reset time</th>
                    </tr>
                    </thead>
                    <tbody>
                    {{--<?php--}}
                    {{--\Microit\LaravelAdminLightspeed\LightspeedRatelimit::checkUpdate();--}}
                    {{--?>--}}
                    {{--@foreach(\Microit\LaravelAdminLightspeed\LightspeedRatelimit::orderBy('limit')->get() as $limit)--}}
                    {{--<tr>--}}
                    {{--<td>{{ $limit['name'] }}</td>--}}
                    {{--<td class="text-right">{{ $limit['remaining'] }} / {{ $limit['limit'] }}</td>--}}
                    {{--<td>{{ $limit['resetTime']->format('H:i:s') }}</td>--}}
                    {{--</tr>--}}
                    {{--@endforeach--}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>