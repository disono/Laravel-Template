{{--
 * @author      Archie, Disono (webmonsph@gmail.com)
 * @link        https://github.com/disono/Laravel-Template
 * @lincense    https://github.com/disono/Laravel-Template/blob/master/LICENSE
 * @copyright   Webmons Development Studio
--}}

@if(isset($export))
    <a class="nav-link {{ isActiveMenu('admin.csv.export') }}"
       href="{{ route('admin.csv.export', array_merge(['source' => $csvSource], $request->all())) }}"><i
                class="fas fa-download"></i> Export CSV</a>
@else
    <a class="nav-link {{ isActiveMenu('admin.csv.import') }}"
       href="{{ route('admin.csv.import', array_merge(['source' => $csvSource], $request->all())) }}"><i
                class="fas fa-upload"></i> Import CSV</a>
    <a class="nav-link {{ isActiveMenu('admin.csv.export') }}"
       href="{{ route('admin.csv.export', array_merge(['source' => $csvSource], $request->all())) }}"><i
                class="fas fa-download"></i> Export CSV</a>
    <a class="nav-link {{ isActiveMenu('admin.csv.template') }}"
       href="{{ route('admin.csv.template', array_merge(['source' => $csvSource], $request->all())) }}"><i
                class="fas fa-newspaper"></i> CSV Template</a>
@endif