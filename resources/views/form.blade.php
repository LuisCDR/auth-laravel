<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <title>Form</title>
</head>
<body>
    @if(session('status'))
        <div class="alert">{{session('status')}}</div>
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
    <div class="card m-5" style="width: 30%;">
        <div class="card-body">
            <h3 class="card-title">Notas</h3>
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
                    <input type="submit" class="btn btn-dark" name="action" value="export">
                </div>
            </form>
        </div>
    </div>
</body>
</html>