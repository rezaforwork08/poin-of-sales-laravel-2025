 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">

         <li class="nav-item">
             <a class="nav-link collapsed" href="index.html">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li><!-- End Dashboard Nav -->


         <li class="nav-item">
             <a class="nav-link collapsed" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
                 <i class="bi bi-journal-text"></i><span>Transaction</span><i class="bi bi-chevron-down ms-auto"></i>
             </a>
             <ul id="forms-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a href="{{ route('order.index') }}">
                         <i class="bi bi-circle"></i><span>View All</span>
                     </a>
                 </li>
                 <li>
                     <a href="forms-layouts.html">
                         <i class="bi bi-circle"></i><span>Add Transaction</span>
                     </a>
                 </li>
                 <li>
                     <a href="forms-editors.html">
                         <i class="bi bi-circle"></i><span>Report</span>
                     </a>
                 </li>

             </ul>
         </li><!-- End Forms Nav -->

         <li class="nav-item">
             <a class="nav-link collapsed" data-bs-target="#tables-nav" data-bs-toggle="collapse" href="#">
                 <i class="bi bi-layout-text-window-reverse"></i><span>Master Data</span><i
                     class="bi bi-chevron-down ms-auto"></i>
             </a>
             <ul id="tables-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a href="{{ route('product.index') }}">
                         <i class="bi bi-circle"></i><span>Master Product</span>
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('category.index') }}">
                         <i class="bi bi-circle"></i><span>Master Category</span>
                     </a>
                 </li>
                 <li>
                     <a href="{{ route('user.index') }}">
                         <i class="bi bi-circle"></i><span>Master User</span>
                     </a>
                 </li>
             </ul>
         </li><!-- End Tables Nav -->

         <li class="nav-heading">Pages</li>

         <li class="nav-item">
             <a class="nav-link collapsed" href="users-profile.html">
                 <i class="bi bi-person"></i>
                 <span>Profile</span>
             </a>
         </li><!-- End Profile Page Nav -->



     </ul>

 </aside><!-- End Sidebar-->
