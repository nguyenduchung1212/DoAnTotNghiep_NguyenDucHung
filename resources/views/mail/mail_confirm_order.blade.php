<?php $url = 'search-order?code_invoice=' ;
?>
<h2>Gửi {{$name}},</h2>
<p>Đơn hàng của bạn đã được tiếp nhận</p>
<p>Mã đơn hàng của bạn là {{$code}} </p>
<p>Nhấn vào link để xem tình trạng đơn hàng {{Config::get('app.url').$url.$code}} </p>
<p>Cảm ơn bạn đã tin tưởng </p>