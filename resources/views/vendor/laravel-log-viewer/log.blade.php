@extends('admin.layouts.app')

@section('page-title') Logs @stop

@section('breadcrumb1')<li class="breadcrumb-item">Settings </li>@stop
@section('breadcrumb2')<li class="breadcrumb-item">Edit Profile </li>@stop

@section('main_content')
  <script>
    function initTheme() {
      const darkThemeSelected =
        localStorage.getItem('darkSwitch') !== null &&
        localStorage.getItem('darkSwitch') === 'dark';
      darkSwitch.checked = darkThemeSelected;
      darkThemeSelected ? document.body.setAttribute('data-theme', 'dark') :
        document.body.removeAttribute('data-theme');
    }

    function resetTheme() {
      if (darkSwitch.checked) {
        document.body.setAttribute('data-theme', 'dark');
        localStorage.setItem('darkSwitch', 'dark');
      } else {
        document.body.removeAttribute('data-theme');
        localStorage.removeItem('darkSwitch');
      }
    }
  </script>
<div class="container-fluid">
    <div class="container">
        <div class="custom-control custom-switch" style="padding-bottom:20px;">
            <input type="checkbox" class="custom-control-input" id="darkSwitch">
        </div>
        <div class="list-group div-scroll">
            @foreach($folders as $folder)
                <div class="list-group-item">
                    <a href="?f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}">
                        <span class="fa fa-folder"></span> {{$folder}}
                    </a>
                    @if ($current_folder == $folder)
                        <div class="list-group folder">
                            @foreach($folder_files as $file)
                                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}&f={{ \Illuminate\Support\Facades\Crypt::encrypt($folder) }}"
                                   class="list-group-item @if ($current_file == $file) llv-active @endif">
                                    {{$file}}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
            @foreach($files as $file)
                <a href="?l={{ \Illuminate\Support\Facades\Crypt::encrypt($file) }}"
                   class="list-group-item @if ($current_file == $file) llv-active @endif">
                    {{$file}}
                </a>
            @endforeach
        </div>
    </div>
<br>
    <div class="row">
    <div class="col-12 table-container table-responsive">
      @if ($logs === null)
        <div>
          Log file >50M, please download it.
        </div>
      @else
        <table id="table-log" class="table table-striped" data-ordering-index="{{ $standardFormat ? 2 : 0 }}">
          <thead>
          <tr>
            @if ($standardFormat)
              <th>Level</th>
              <th>Context</th>
              <th>Date</th>
            @else
              <th>Line number</th>
            @endif
            <th>Content</th>
          </tr>
          </thead>
          <tbody>
          @foreach($logs as $key => $log)
            <tr data-display="stack{{{$key}}}">
              @if ($standardFormat)
                <td class="nowrap text-{{{$log['level_class']}}}">
                  <span class="fa fa-{{{$log['level_img']}}}" aria-hidden="true"></span>&nbsp;&nbsp;{{$log['level']}}
                </td>
                <td class="text">{{$log['context']}}</td>
              @endif
              <td class="date">{{{$log['date']}}}</td>
              <td class="text">
                {{{$log['text']}}}
                @if (isset($log['in_file']))
                  <br/>{{{$log['in_file']}}}
                @endif
                @if ($log['stack'])
                  <div class="stack" id="stack{{{$key}}}"
                       style="display: none; white-space: pre-wrap;">{{{ trim($log['stack']) }}}
                  </div>
                @endif
              </td>
            </tr>
          @endforeach

          </tbody>
        </table>
      @endif
      <div class="p-3">
        @if($current_file)
          <a href="?dl={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-download"></span> Download file
          </a>
          -
          <a id="clean-log" href="?clean={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-sync"></span> Clean file
          </a>
          -
          <a id="delete-log" href="?del={{ \Illuminate\Support\Facades\Crypt::encrypt($current_file) }}{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
            <span class="fa fa-trash"></span> Delete file
          </a>
          @if(count($files) > 1)
            -
            <a id="delete-all-log" href="?delall=true{{ ($current_folder) ? '&f=' . \Illuminate\Support\Facades\Crypt::encrypt($current_folder) : '' }}">
              <span class="fa fa-trash-alt"></span> Delete all files
            </a>
          @endif
        @endif
      </div>
    </div>
  </div>
</div>
{{--<!-- jQuery for Bootstrap -->--}}
{{--<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"--}}
{{--        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"--}}
{{--        crossorigin="anonymous"></script>--}}
{{--<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"--}}
{{--        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"--}}
{{--        crossorigin="anonymous"></script>--}}
{{--<!-- FontAwesome -->--}}
{{--<script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>--}}
{{--<!-- Datatables -->--}}
{{--<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>--}}
{{--<script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>--}}


@stop
@section('page-scripts')
    <script>

        // dark mode by https://github.com/coliff/dark-mode-switch
        const darkSwitch = document.getElementById('darkSwitch');

        // this is here so we can get the body dark mode before the page displays
        // otherwise the page will be white for a second...
        initTheme();

        window.addEventListener('load', () => {
            if (darkSwitch) {
                initTheme();
                darkSwitch.addEventListener('change', () => {
                    resetTheme();
                });
            }
        });

        // end darkmode js

        $(document).ready(function () {
            $('.table-container tr').on('click', function () {
                $('#' + $(this).data('display')).toggle();
            });
            $('#table-log').DataTable({
                "order": [$('#table-log').data('orderingIndex'), 'desc'],
                "stateSave": true,
                "stateSaveCallback": function (settings, data) {
                    window.localStorage.setItem("datatable", JSON.stringify(data));
                },
                "stateLoadCallback": function (settings) {
                    var data = JSON.parse(window.localStorage.getItem("datatable"));
                    if (data) data.start = 0;
                    return data;
                }
            });
            $('#delete-log, #clean-log, #delete-all-log').click(function () {
                return confirm('Are you sure?');
            });
        });
    </script>
    @stop
