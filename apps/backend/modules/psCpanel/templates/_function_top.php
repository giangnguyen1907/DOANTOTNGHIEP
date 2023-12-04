<style>

.demo-btns>li {
    display: inline-block;
    list-style: none;
    margin: 0px 20px;
	float: left;
}
.demo-btns>li>a {
    padding: 17px 0px;
    width: 70px;
    height: 70px;
    border-radius: 50%;
    font-size: 18px;
}
</style>

<ul class="demo-btns" style="float: left; margin: 5px 0px;">
	<li>
		<a href="<?=url_for ( '@ps_attendances' )?>" class="btn btn-success"><i class="fa fa-2x fa-clock-o"></i></a>
		<p>Điểm danh</p>
	</li>
	<li>
		<a href="<?=url_for ( '@ps_cms_notifications_ps_cms_notification' )?>" class="btn btn-info"><i class="fa fa-2x fa-bell-o"></i></a>
		<p>Thông báo</p>
	</li>
	<li>
		<a href="<?=url_for ( '@ps_students' )?>" class="btn btn-danger"><i class="fa fa-2x fa-child"></i></a>
		<p>Học sinh</p>
	</li>
	<li>
		<a href="<?=url_for ( '@ps_class' )?>" class="btn btn-primary"><i class="fa fa-2x fa-television"></i></a>
		<p>Lớp học</p>
	</li>
	<li>
		<a href="#" class="btn btn-danger"><i class="fa fa-2x fa-shirtsinbulk"></i></a>
		<p>Bán hàng</p>
	</li>
	<li>
		<a href="#" class="btn btn-warning"><i class="fa fa-2x fa-money"></i></a>
		<p>Bán hàng</p>
	</li>
	<li>
		<a href="#" class="btn btn-danger"><i class="fa fa-2x fa-exchange"></i></a>
		<p>Bán hàng</p>
	</li>
</ul>