<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-sidebar="dark"
    data-sidebar-size="lg" data-preloader="disable" data-theme="default" data-bs-theme="light" data-topbar="light">
<title>الاحداث</title>
<meta content="Minimal Admin & dashboard Template" name="description">
@include('main.meta')
</head>

<body>
    <div id="layout-wrapper">
        @include('main.topbar')
        @include('main.sidebar')
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">الاحداث</h4>
                                </div>
                                @if (Request::url() == route('events.all'))
                                    <!-- Base Example -->
                                    <div class="accordion" id="default-accordion-example">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                    aria-expanded="true" aria-controls="collapseOne">
                                                    فلتر
                                                </button>
                                            </h2>
                                            <div id="collapseOne" class="accordion-collapse collapse "
                                                aria-labelledby="headingOne"
                                                data-bs-parent="#default-accordion-example">
                                                <div class="accordion-body">
                                                    <form action="{{ route('events.filter') }}" method="post">
                                                        @csrf
                                                    <div class="my-2">
                                                        <input type="text" class="form-control" name="text" placeholder="ابحث عن الحدث">
                                                    </div>                                               
                                                    <div class="my-2">
                                                        <div>
                                                            <input type="date" class="form-control" name="event_date_from" placeholder="اختر تاريخ البداية" value="{{ old('event_date_from', request('event_date_from')) }}">
                                                        </div>
                                                    </div>
                                                    <div class="my-3">
                                                        <div>
                                                            <input type="date" class="form-control" name="event_date_to" placeholder="اختر تاريخ النهاية" value="{{ old('event_date_to', request('event_date_to')) }}">
                                                        </div>
                                                    </div>
                                                    <button type="submit" class="btn btn-primary" >فلتر</button>

                                                    </form>                                             
                                                 </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <div>
                                        <table id="buttons-datatables"
                                            class="table buttons nowrap align-middle text-center" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>التاريخ</th>
                                                    <th>المشاركة</th>
                                                    <th>الصورة</th>
                                                    <th>الأسماء</th>
                                                    <th>الأرقام</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (count($events) > 0)
                                                    @foreach ($events as $event)
                                                        <tr>
                                                            <td rowspan="{{ count($event->volunteers) + 1 }}">
                                                                {{ $event->date }}</td>


                                                            <td rowspan="{{ count($event->volunteers) + 1 }}">
                                                                {{ $event->type }}</td>
                                                            <td rowspan="{{ count($event->volunteers) + 1 }}">
                                                                @if ($event->getMedia('event_screns')->first() !== null)
                                                                    <img alt="profile" height="75" width="75"
                                                                        class="object-fit-cover text-center my-3"
                                                                        src="
                                                   
                                                   
                                                       {{ $event->getMedia('event_screns')->first()->getUrl() }}
                                                       ">
                                                                @endif
                                                            </td>
                                                            @foreach ($event->volunteers as $volunteer)
                                                        <tr>
                                                            <td>{{ $volunteer->name }}</td>
                                                            <td>{{ $volunteer->phone }}</td>
                                                        </tr>
                                                    @endforeach

                                                    </tr>
                                                @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                    @if (Request::url() == route('events.all'))
                                    {{ $events->links() }}
                                    @endif
                                </div><!-- end card-body -->
                            </div><!-- end card -->
                        </div>

                    </div>
                </div>
            </div>
            @include('main.footer')
        </div>
    </div>
    @include('main.scripts')
</body>

</html>
