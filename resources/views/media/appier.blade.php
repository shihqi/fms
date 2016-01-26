<?php print '<?xml version="1.0" encoding="UTF-8" ?>'; ?>
<rss xmlns:g="http://base.google.com/ns/1.0" version="2.0">
    <channel>
        <title>{!! $feed->first()->description !!}</title>
        <link>{!! URL::full() !!}</link>
        <description>{!! $feed->first()->description !!}</description>
        <totalrecord>{!! $total !!}</totalrecord>
        @if ($products->count() > 0)
            @foreach ($products as $product)
        <item>
            <g:id><![CDATA[{!!$product->id !!}]]></g:id>
            <g:title><![CDATA[{!! trim($product->name) !!}]]></g:title>
            <g:description><![CDATA[{!!$product->description !!}]]></g:description>
            <g:image_link><![CDATA[{!!$product->image !!}]]></g:image_link>
            <link><![CDATA[{!!$product->url !!}]]></link>
            <g:price>{!!$product->price !!} TWD</g:price>
            <g:brand>{!!$product->brand !!}</g:brand>
            <g:google_product_category><![CDATA[{!!$product->google_category !!}]]></g:google_product_category>
            <g:product_type><![CDATA[{!!$product->category !!}]]></g:product_type>    
            <g:availability>{!!$product->availability !!}</g:availability>
            <g:condition>{!!$product->condition !!}</g:condition>
        </item>
            @endforeach
        @endif
    </channel>
    <Updated>{!! date("Y-m-d H:i:s") !!}</Updated>
</rss>
