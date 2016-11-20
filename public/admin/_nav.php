<nav class="top-bar" data-topbar role="navigation">
	<ul class="title-area">
		<li class="name"><h1><a href="/">betterquiz</a></h1></li> 
		<li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
	</ul>
	<section class="top-bar-section">
		<ul class="right">
			<li class="has-dropdown">
				<a href="#"><?php echo $user->Email; ?></a>
				<ul class="dropdown">
					<li><a href="logout.php">Logout</a></li>
				</ul>
			</li>
		</ul> 

		<ul class="left">
			<li class="has-dropdown">
				<a href="#">Quiz</a>
				<ul class="dropdown">
					<li><a href="quiz-list.php">Find</a></li>
					<li class="divider"></li>
					<li><a href="quiz-new.php"><i class="fa fa-plus-square"></i> New Quiz</a></li>
				</ul>
			</li>
			<li class="has-dropdown">
				<a href="#">Users</a>
				<ul class="dropdown">
					<li><a href="user-list.php">Find</a></li>
					<li class="divider"></li>
					<li><a href="merge-form.php">Merge</a></li>
					<!-- <li><a href="/course/edit/0"><i class="fa fa-plus-square"></i>New User</a></li> -->
				</ul>
			</li>
			<li>
				<a href="admins-list.php">Admins</a>
			</li>
		</ul> 
	</section> 
</nav>