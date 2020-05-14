@extends('layouts.app')
@section('css')
<style>
    .book-card {
        height: 95%;
    }
</style>
@endsection
@section('content')
@if($title == "Total")
{{ Breadcrumbs::render('author') }}
@else
{{ Breadcrumbs::render('authors',$title) }}
@endif
<div class="row" id="author_app">
    <div class="col-md-3 mt-2">
        <div class="row">
            <div class="list-group col ml-3 ">
                <a class="list-group-item list-group-item-action active  ii-title">
                    Author(s)
                </a>
                <a :href="'{{URL::to('/')}}/author/'+author.name.split(' ').join('_').toLowerCase()"
                    class="list-group-item list-group-item-action" v-for="author in authors">@{{ author.name }} </a>
            </div>
        </div>
    </div>
    <div class="col-md-9 mt-2 mb-2">
        <div class="card">
            <div class="card-header">
                {{ $title ." Book(s) - ". $books->total() }}
            </div>
            <div class="card-body">
                <div class="row ml-2 no-gutters">
                    @if($books->total() != 0)
                    @foreach($books as $book)
                    <div class="col-4">
                        <a href="{{URL::to('book/detail')}}/{{str_replace(' ','_',strtolower($book->name))}}"
                            class="disable-link-color">
                            <div class="card book-card mx-2 pb-3">
                                <img class="card-img-top"
                                    src="{{ URL::to(str_replace('images','images/thumbnail',$book->image_name)) }}"
                                    alt="{{ $book->name }}">
                                <div class="card-body">
                                    <h5 class="card-title"> {{ $book->name }}</h5>
                                    <h5 class="card-title">By {{ $book->authors->name }}</h5>
                                    <label>USD - ${{ $book->price }}</label><br />
                                    <label>Downloads - {{ $book->download}} </label>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                    @else
                    <blockquote class="blockquote">
                        <p class="mb-0">No book(s) for such author.</p>
                    </blockquote>
                    @endif
                </div>
                <div class="float-right">
                    {{ $books->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    const app = new Vue({
        el:'#author_app',
        data:{
            authors : {},
            books : {}
        },
        methods: {
            getAuthors(){
                axios.get(app_url+`/api/author/all`)
                .then((response) =>{
                    this.authors = response.data;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listen(){
                Echo.channel('author.new')
                .listen('NewAuthor',(author)=>{
                    this.authors.unshift(author);
                });
                Echo.channel('author.edit')
                .listen('EditAuthor',()=>{
                    this.getAuthors();
                });
            }
        },
        mounted (){
            this.getAuthors();
            this.listen();
        }
    })
</script>
@endsection