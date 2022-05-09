@extends('products.layout')
 
@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>products</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
            </div>
        </div>
    </div>
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
    <div class="row">
        <?php
            //Columns must be a factor of 12 (1,2,3,4,6,12)
            $numOfCols = 4;
            $rowCount = 0;
            $bootstrapColWidth = 12 / $numOfCols;
            foreach ($products as $product){
            if($rowCount % $numOfCols == 0) { ?> <div class="row"> <?php } 
                $rowCount++; ?>  
                    <div class="col-md-<?php echo $bootstrapColWidth; ?> "style="background-color:gray;margin:5px;padding:5px">
                        <p>{{ $product->name }}</p>
                        <p>{{ $product->detail }}</p>
                         <p>{{ $product->price }} Rs</p>
                        
                        <form action="{{ route('products.destroy',$product->id) }}" method="POST">
   
                            <a class="btn btn-info" href="{{ route('products.show',$product->id) }}">Buy Now</a>
            
                            <a class="btn btn-primary" href="{{ route('products.edit',$product->id) }}">Edit</a>
           
                            @csrf
                            @method('DELETE')
              
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
            <?php
                if($rowCount % $numOfCols == 0) { ?> </div> <?php } } ?>
    </div>
  
    {!! $products->links() !!}
      
@endsection