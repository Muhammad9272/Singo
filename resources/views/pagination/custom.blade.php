@if ($prods->lastPage() > 1)
    <?php
        $start = $prods->CurrentPage() - 3; // show 3 pagination links before current
        $end = $prods->CurrentPage() + 3; // show 3 pagination links after current
        if($start < 1) $start = 1; // reset start to 1
        if($end >= $prods->LastPage() ) $end = $prods->LastPage(); // reset end to last page
    ?>
    <a class="btn btn-default btn-xs {{ ($prods->CurrentPage() == 1) ? ' disabled' : '' }}" href="{{ $prods->Url(1) }}"><i class="fa fa-angle-left"></i></a>
    
    @if($start>1)
        <a class="btn btn-default btn-xs" href="{{ $prods->Url(1) }}">{{1}}</a> ...
    @endif
    @for ($i = $start; $i <= $end; $i++)
        <a class="btn btn-default btn-xs {{ ($prods->CurrentPage() == $i) ? ' active' : '' }}" href="{{ $prods->Url($i) }}">{{$i}}</a>
    @endfor
    @if($end<$prods->LastPage())
       ... <a class="btn btn-default btn-xs" href="{{ $prods->Url($prods->LastPage()) }}">{{$prods->LastPage()}}</a>
    @endif

    <a class="btn btn-default btn-xs {{ ($prods->CurrentPage() == $prods->LastPage()) ? ' disabled' : '' }}" href="{{ $prods->Url($prods->CurrentPage()+1) }}"><i class="fa fa-angle-right"></i></a>
@endif