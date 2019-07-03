{{--
 * @author      Archie Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @license     https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@if(isset($export))
    <div class="btn-group" role="group">
        <a href="{{ route('admin.csvExport.browse', array_merge(['source' => $csvSource], $request->all())) }}"
           class="btn btn-light"><i class="fas fa-download"></i> Export CSV</a>
    </div>
@else
    <div class="btn-group" role="group">
        <a href="{{ route('admin.csvImport.create', array_merge(['source' => $csvSource], $request->all())) }}"
           class="btn btn-light"><i class="fas fa-upload"></i> Import CSV</a>

        <a href="{{ route('admin.csvExport.browse', array_merge(['source' => $csvSource], $request->all())) }}"
           class="btn btn-light"><i class="fas fa-download"></i> Export CSV</a>

        <a href="{{ route('admin.csvTemplate.browse', array_merge(['source' => $csvSource], $request->all())) }}"
           class="btn btn-light"><i class="fas fa-list"></i> CSV Template</a>
    </div>
@endif