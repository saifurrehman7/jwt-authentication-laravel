<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
        }

        body {
            background: linear-gradient(-45deg, #1e3c72, #2a5298, #6b93d6, #a3c9f9);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
        }

        @keyframes gradient {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }
        .la-edit{
            font-size: 20px;
            color: blue;
        }
        .la-trash{
            font-size: 20px
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Navbar</a>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-flex align-items-center">
            <a class="nav-link text-dark  "><span class="badge badge-secondary">{{Session::get('user_account_session.name')}}</span></a>
          </li>  
          <li class="nav-item">
            <a class="nav-link text-dark" href="{{url('product-page')}}">Products</a>
          </li>  
        @if(Session::get('user_account_session.role_id') == '1' || Session::get('user_account_session.role_id') == '2')
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{url('user-list')}}">Give permissions</a>
          </li>  
          @endif
        <li class="nav-item">
          <a class="nav-link text-dark" href="{{url('log-out')}}">Logout</a>
        </li>  
    </ul>
</nav>
<section class="sign_up padding-top">
    <div class="container auto-container">
        <div class="row no-gutters justify-content-center">
            <div class="col-6 my-4">
                @if (Session::has('success'))
                <div class="alert alert-success mb-0 text-center">
                    {{ Session::get('success') }}
                </div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert-danger mb-0 text-center">
                    {{ Session::get('error') }}
                </div>
                @endif
                <div class="js-alerts"></div>
            </div>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-10 bg-white">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Role</th>
                            @if(Session::get('user_account_session.role_id') == '1')
                            <th scope="col">Edit Role</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($getUsers as $value)
                        <tr>
                            <th scope="row">{{$value->id}}</th>
                            <td>{{$value->name}}</td>
                            <td>{{$value->email}}</td>
                            <td>@if($value->role_id == '1') Admin @elseif($value->role_id == '2') Manager @elseif($value->role_id =='3') User @endif</td>
                            @if(Session::get('user_account_session.role_id') == '1')
                            <td>
                                <a href="javascript:void(0);" onclick="changeUserRole('{{$value->id}}', '{{$value->role_id}}')" data-toggle="modal" data-target="#exampleModal" class="list-action-btn">
                                    <i class="las la-edit"></i>
                                </a>
                                <a href="{{url('delete-user')}}/{{$value->id}}"><i class="las la-trash text-danger font-20"></i></a> 
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Edit User Role</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('change-user-role')}}" class="myform">
                @csrf
                <div id="course-delivery-detail-result"></div>
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="up_user_role">Role</label>
                            <select name="up_user_role" id="up_user_role" class="form-control">
                                <option value="1">Admin</option>
                                <option value="2">Manager</option>
                                <option value="3">User</option>
                            </select>
                        </div>
                        <input type="hidden" name="up_user_id" id="up_user_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-link" id="save-course-delivery-detail">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function changeUserRole(userId, roleId) { 
        document.getElementById('up_user_id').value = userId;
        document.getElementById('up_user_role').value = roleId;
    }

    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000); // 3000 milliseconds = 3 seconds
</script>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

</body>
</html>