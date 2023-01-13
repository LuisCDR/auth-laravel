<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title>Notas</title>
    {{--<script type="text/javascript" src="{{!!asset('/js/app.js')!!}}"></script>--}}
</head>
<body>
    @if(session('success'))
        <div class="alert alert-success">{{session('success')}}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{session('danger')}}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="d-flex">
        <div class="card m-5" style="width: 30%;">
            <div class="card-body">
                <h4 class="card-title">Guardar</h4>
                <form name="notas" action="{{url('perform')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="not_tit" class="form-label">Título</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" name="not_tit" id="not_tit" placeholder="titulo">
                    </div>
                    <div class="form-group">
                        <label for="not_des" class="form-label">Descripción</label>
                        <textarea name="not_des" class="form-control" id="not_des" placeholder="descripcion" cols="30" rows="8"></textarea>
                    </div>
                    <div class="d-flex flex-row justify-content-evenly m-2">
                        <input type="submit" class="btn btn-primary" name="action" value="store">
                        {{--<input type="submit" class="btn btn-dark" name="action" value="export">--}}
                    </div>
                </form>
            </div>
        </div>
        <div class="card m-5">
            <div class="card-body">
                <h4 class="card-title">Listado</h4>
                @if(session('message'))
                    <div class="alert alert-info">{{session('message')}}</div>
                @endif
                @if(count($notas) > 0)
                <form action="{{url('export')}}" method="post">
                    @csrf
                    <div class="card-body form-group">
                        @foreach($notas as $nota)
                            <h5 class="card-title">
                                <input class="card-text border border-0 disabled" type="text" id="not_tit" name="not_tit" value="{{$nota->not_tit}}">
                            </h5>
                            <textarea class="card-text border border-0 disabled" cols="30" rows="4" id="not_des" name="not_des">
                                {{$nota->not_des}}
                            </textarea>
                        @endforeach
                    </div>
                    <div class="d-flex flex-row justify-content-evenly m-2">
                        <input class="btn btn-dark" type="submit" name="action" value="Exportar">
                    </div>
                </form>
                @else
                    <div class="alert alert-info">No se encontro ninguna nota</div>
                @endif
                {{--<x-button-export data="{{!!$notas!!}}"></x-button-export>--}}
            </div>
        </div>
    </div>
</body>
</html>