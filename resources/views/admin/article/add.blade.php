@extends('admin.layout.index');

@section('content')

    <form action="/admin/article/insert" method="post">
    {{ csrf_field() }}
    标题:   <input type="text" name="title"><br><br>
            <!-- 加载编辑器的容器 -->
    介绍:   <script id="container" name="content" type="text/plain">
            </script><br><br>
            <input type="submit" value="提交">

    </form>
     

    <!-- 配置文件 -->
    <script type="text/javascript" src="/u/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/u/ueditor.all.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>
@endsection