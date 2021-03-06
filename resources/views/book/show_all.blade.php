@extends('layouts.app')
@section('css')

@endsection
@section('content')
{{ Breadcrumbs::render('book-list') }}
<div id="books">
    <div class="flash-message row mt-2 mb-2 justify-content-md-center">
        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
          @if(Session::has($msg))
          <p class="alert alert-{{ $msg }}">{!! Session::get($msg) !!} <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a></p>
          @endif
        @endforeach
    </div>
    <div class="row mt-2 mb-2 justify-content-md-center"> 
        <div class="card ">
            <div class="card-header">
                <label class="card-title"> Show All Books</label>
                <label class="float-right"> <a href="{{url('admin/book/create')}}"> Create New Book</a></label>
            </div>
            <div class="card-body">
                <table class="table table-hover table-responsive">
                    <thead>
                        <th>Name</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Options</th>
                    </thead>
                    <tr v-for="book in books">
                        <td> 
                            <a :href="'{{URL::to('/')}}/book/detail/'+book.name.split(' ').join('_').toLowerCase()" > 
                                @{{book.name}} 
                            </a>
                        </td>
                        <td> @{{book.authors.name}}</td>
                        <td> @{{book.genres.name}}</td>
                        <td> @{{book.price}}</td>
                        <td>
                            <a :href="'{{URL::to('/')}}/admin/book/edit/'+book.id" >Edit </a>
                             &nbsp;
                            <a :href="'{{URL::to('/')}}/admin/book/delete/'+book.id" >Delete </a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>  
    </div>
</div>
@endsection
@section('scripts')
<script>
	const app = new Vue({
		el:'#books',
		data:{
			books : {!! $books !!},

		},
        methods: {
            getBooks(){
                axios.get(app_url+`/api/book/all`)
                .then((response) =>{
                    this.books = response.data;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listen(){
                Echo.channel('book.new')
                .listen('NewBook',(book)=>{
                    this.books.unshift(book);
                });
            }
        },
        mounted (){
            this.getBooks();
            this.listen();
        }
    })
</script>
@endsection
