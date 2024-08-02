<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        html, body {
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
            <a class="nav-link text-dark pb-0"><span class="badge badge-secondary">{{ Session::get('user_account_session.name') }}</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('product-page') }}">Products</a>
        </li>
        @if(Session::get('user_account_session.role_id') == '1' || Session::get('user_account_session.role_id') == '2')
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('user-list') }}">Give permissions</a>
        </li>
        @endif
        <li class="nav-item">
            <a class="nav-link text-dark" href="{{ url('log-out') }}">Logout</a>
        </li>
    </ul>
</nav>
<section class="sign_up padding-top">
    <div class="container auto-container">
        <div class="row no-gutters justify-content-center">
            <div class="col-4 my-4">
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
            <div class="col-12">
                <p class="text-white" style="font-weight:bold">Hi {{ Session::get('user_account_session.name')}}. Welcome to our dashboard. Your current role is  @if(Session::get('user_account_session.role_id') == '1') Admin @elseif(@Session::get('user_account_session.role_id') == '2') Manager @else User @endif.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-12 bg-white p-3">
                <div class="content-inner-div mb-3">
                    <div class="text-left simple-accordion-header">
                        <h4 class="mb-0 d-flex justify-content-between"><span>Products</span><i class="las la-angle-up"></i></h4>
                    </div>
                    <div class="simple-accordion-content mt-4">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">ID</th>
                                        <th scope="col">Product Name</th>
                                        <th scope="col">Product Title</th>
                                        <th scope="col">Product Price</th>
                                        <th scope="col">Added By</th>
                                        @if(Session::get('user_account_session.role_id') == '1' || Session::get('user_account_session.role_id') == '2')
                                        <th scope="col">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($getProducts as $value)
                                    <tr>
                                        <td>{{ $value->id }}</td>
                                        <td>{{ $value->product_name }}</td>
                                        <td>{{ substr($value->product_title, 0, 40) }}{{ strlen($value->product_title) > 40 ? '...' : '' }}</td>
                                        <td>${{ $value->product_price }}</td>
                                        <td>{{ $value->added_by }}</td>
                                        <td>
                                            @if(Session::get('user_account_session.role_id') == '1' || Session::get('user_account_session.role_id') == '2') 
                                            <a href="javascript:void(0);" onclick="changePrice('{{ $value->id }}', '{{ $value->product_name }}', '{{ $value->product_title }}', '{{ $value->product_price }}')" data-toggle="modal" data-target="#editModal" class="list-action-btn">
                                                <i class="las la-edit"></i>
                                            </a>
                                            @endif
                                            @if(Session::get('user_account_session.role_id') == '1')
                                            <a href="{{ url('delete-product') }}/{{ $value->id }}"><i class="las la-trash text-danger font-20"></i></a> 
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                @if(Session::get('user_account_session.role_id') == '1')
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="p-1"><a href="javascript:void(0);" data-toggle="modal" data-target="#exampleModal" class="btn btn-outline-primary rounded-pill mt-4"><i class="feather icon-plus mr-2"></i>Add Item</a></td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('add-product') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product name</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="text" class="form-control" id="product_name" name="product_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product title</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="text" class="form-control" id="product_title" name="product_title" required>
                              
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product price</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="number" class="form-control" id="product_price" name="product_price" required step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-link" id="save-featured-course">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Add Product</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{ url('edit-product') }}">
                @csrf
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product name</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="hidden" id="up_p_id" name="up_p_id">
                                <input type="text" class="form-control" id="up_p_name" name="up_p_name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product title</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="text" class="form-control" id="up_p_title" name="up_p_title" required>
                              
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-lg-12 banner-text">
                            <label for="inputEmail4">Product price</label>
                            <div class="col-lg-12 pr-0 pl-0">
                                <input type="number" class="form-control" id="up_p_price" name="up_p_price" required step="0.01">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-link" id="save-featured-course">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.9.2/parsley.min.js"></script>

<script>
    setTimeout(function() {
        $('.alert').fadeOut('fast');
    }, 3000); // 5000 milliseconds = 5 seconds
 

    function changePrice(id, name,title,price) { 
            $('#up_p_id').val(id);
            $('#up_p_name').val(name);
            $('#up_p_title').val(title);
            $('#up_p_price').val(price);
        }

</script>

</body>
</html>
