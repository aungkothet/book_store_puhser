@extends('layouts.app')
@section('css')

@endsection
@section('content')
<div class="row mt-2" id="genre_app">
    <div class="col-md-3 mt-2">
        <div class="row">
            <div class="list-group col ml-3 ">
                <a href="{{ URL::to('genre/all') }}" class="list-group-item list-group-item-action active">
                Categories
            </a>
            <a :href="'{{URL::to('/')}}/genre/'+genre.name.split(' ').join('_').toLowerCase()" class="list-group-item list-group-item-action" v-for="genre in genres">@{{ genre.name }} </a>
            </div>
        </div>
    </div>
     <div class="col-md-9 mt-2 mb-2"> 
        <div class="card">
            <div class="card-header">
            {{ $title . $books->total() }}
            </div>
            <div class="card-body">
                <div class="row ml-2">
                    @if($books->total() != 0)
                        @foreach($books as $book)
                        <div class="card mr-4 mb-3 ml-1" style="width: 14rem;">
                          <img class="card-img-top" src="{{ url( $book->image_name ) }}" width="304" height="180" alt="Card image cap">
                          <div class="card-body">
                            <h5 class="card-title">{{ $book->name }}</h5>
                            <h3> Price -${{ $book->price }}</h3>                
                            <a href="{{URL::to('book/detail')}}/{{str_replace(' ','_',strtolower($book->name))}}" class="btn btn-primary">View Detail</a>

                          </div>
                        </div>
                        @endforeach 
                    @else
                    <blockquote class="blockquote">
                      <p class="mb-0">No book(s) for such category.</p>
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
        el:'#genre_app',
        data:{
            genres : {},
            books : {}
        },
        methods: {
            getGenres(){
                axios.get(app_url+`/api/genre/all`)
                .then((response) =>{
                    this.genres = response.data;

                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            listen(){
                Echo.channel('genre.refresh')
                .listen('RefershGenre',()=>{
                    this.getGenres();
                });
               
            }
        },
        mounted (){
            this.getGenres();
            this.listen();
        }
    })
</script>
@endsection
