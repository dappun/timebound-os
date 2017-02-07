<li class="{{ Request::is('timer*') ? 'active' : '' }}">
   <a href="{!! url('/timer') !!}">Timer</a>
</li>

<li class="{{ Request::is('report*') ? 'active' : '' }}">
   <a href="{!! url('/report') !!}">Report</a>

   <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
	    <li><a href="{{ url('/report') }}">Detailed</a></li>

	    @if (\Entrust::hasRole(['project-manager', 'admin']))
	    <li><a href="{{ url('/report/team') }}">Team Weekly</a></li>
	    @endif
	</ul>
</li>

@if (\Entrust::hasRole(['project-manager', 'admin']))
<li class="{{ Request::is('users*') ? 'active' : '' }}">
   <a href="{!! url('/admin/users') !!}">Team</a>
</li>
@endif

@if (\Entrust::hasRole(['project-manager', 'admin']))
<li class="{{ Request::is('projects*') ? 'active' : '' }}">
   <a href="{!! url('/projects') !!}">Projects</a>
</li>
@endif

@if (\Entrust::hasRole(['admin']))
<li class="{{ Request::is('clients*') ? 'active' : '' }}">
   <a href="{!! url('/clients') !!}">Clients</a>
</li>
@endif