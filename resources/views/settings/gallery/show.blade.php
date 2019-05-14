@extends('layouts.app')

@section('title','圖庫')

@section('css')
<link href="{{asset('assets/apps/css/magnific-popup.css')}}" rel="stylesheet" type="text/css" />
<style>
    /* 初始label顏色 */
    .form-group.form-md-line-input.form-md-floating-label .form-control ~ label {
        color: #248ff1; }
    /* help-block顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .help-block, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .help-block {
        color: #248ff1;}
    /* focus後的label顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus {
        color: #248ff1; }
    /* focus後的底線顏色 */
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control.edited:not([readonly]) ~ .form-control-focus:after, .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ label:after,
    .form-group.form-md-line-input .form-control:focus:not([readonly]) ~ .form-control-focus:after {
        background: #248ff1; }
    .form-group.form-md-line-input .form-control::-moz-placeholder {
      color: #248ff1;}
    .form-group.form-md-line-input .form-control:-ms-input-placeholder {
      color: #248ff1; }
    .form-group.form-md-line-input .form-control::-webkit-input-placeholder {
      color: #248ff1; }

    .form-horizontal .form-group.form-md-line-input > label {
        color: #248ff1;
    }

    .image_name{

        word-wrap:break-word;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
</style>

@endsection

@section('page_header')
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">

    <!-- BEGIN THEME PANEL -->
    @include('layouts.theme_panel')    
    <!-- END THEME PANEL -->


    <!-- BEGIN PAGE TITLE-->
    <h1 class="page-title"> 圖庫
        <small></small>
    </h1>
    <!-- END PAGE TITLE-->
    
</div>
<!-- END PAGE BAR -->

<!-- END PAGE HEADER-->
@endsection

@section('content')


<div class="row">

    <div class="col-md-12" >
        <form role="form" action="{{ route('gallery.search') }}" method="POST">
            {{ csrf_field() }}

        <div class="col-md-12" >
            
            <div class="col-md-5">
                <div class="form-group form-md-line-input form-md-floating-label" style="padding-top:25px;">
                    <input type="text" name="search" class="form-control" id="search" value="{{ old('search') }}">
                    <label for="search">名稱搜尋</label>
                    <span class="help-block"></span>
                </div>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-lg" style="background-color: #248ff1;color:#fff;font-size: 16px;margin-top:15px;">搜 尋</button>
            </div>
        </div>

        </form>
    </div>

    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet light">
            <div class="portlet-title">
                @include('includes.messages')            
                <div class="caption font-dark col-md-12">
                    <a href="{{ route('gallery.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> 圖片上傳</a>
                </div>
                <div class="tools"> </div>
            </div>
            <div class="portlet-body">
                

                    <div class="row">

                        @if($images->count())
                            @foreach($images as $image)
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <div class="thumbnail">
                                    @if($image->thumb_name == "file_image.jpg")
                                        <img src="{{asset('assets/apps/img/'.$image->thumb_name)}}" alt="{{$image->name}}">
                                    @else
                                        <img src="{{asset('upload/'.$image->thumb_name)}}" alt="{{$image->name}}">
                                    @endif
                                    <div class="caption">
                                        <h4 class="image_name" style="line-height: 24px;">{{$image->name}}</h4>
                                        <p style="margin-top:6px;">
                                            @if($image->thumb_name != "file_image.jpg")
                                                <a href="javascript:show_image('{{asset('upload/'.$image->file_name)}}');" class="btn btn-primary btn-sm" role="button">預覽</a> 
                                            @endif
                                            <a href="{{ url('settings/file_download',$image->id) }}" class="btn btn-default btn-sm" role="button" download>下載</a>
                                            <a href="javascript:;" class="btn red pull-right btn-sm" role="button" onclick="
                                                if(confirm('確定要刪除嗎 ?')){
                                                    event.preventDefault();
                                                    document.getElementById('delete-form-{{$image->id}}').submit();
                                                } else {
                                                    event.preventDefault();
                                                }">刪除</a>
                                            <form id="delete-form-{{$image->id}}" action="{{ route('gallery.destroy', $image->id) }}" method="post" style="display:none">
                                                {{ csrf_field() }}
                                                {{ method_field('DELETE') }}
                                            </form>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            無任何圖片
                        @endif
                    </div>
                    {{ $images->links() }}
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
        
 
    </div>
</div>




@endsection

@section('scripts')
<script src="{{asset('assets/apps/scripts/jquery.magnific-popup.js')}}" type="text/javascript"></script>

<script>

function show_image(path) {
    $.magnificPopup.open({
        showCloseBtn : false, 
        enableEscapeKey : false,
        closeOnBgClick: true, 
        fixedContentPos: false,
        modal:false,
        type:'image',
        items:{src: path}
    });
}


</script>
@endsection