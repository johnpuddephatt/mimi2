@extends(Config::get('chatter.master_file_extend'))

@section(Config::get('chatter.yields.head'))
<link href="/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.css" rel="stylesheet">
<link href="/vendor/devdojo/chatter/assets/css/chatter.css" rel="stylesheet">
@if($chatter_editor == 'simplemde')
<link href="/vendor/devdojo/chatter/assets/css/simplemde.min.css" rel="stylesheet">
@endif
@stop

@section('content')

<div id="chatter" class="chatter_home">

    @if(Session::has('chatter_alert'))
    <div class="chatter-alert alert alert-{{ Session::get('chatter_alert_type') }}">
        <div class="container">
            <strong><i class="chatter-alert-{{ Session::get('chatter_alert_type') }}"></i>
                {{ Config::get('chatter.alert_messages.' . Session::get('chatter_alert_type')) }}</strong>
            {{ Session::get('chatter_alert') }}
            <i class="chatter-close"></i>
        </div>
    </div>
    <div class="chatter-alert-spacer"></div>
    @endif

    @if (count($errors) > 0)
    <div class="chatter-alert alert alert-danger">
        <div class="container">
            <p><strong><i class="chatter-alert-danger"></i> {{ Config::get('chatter.alert_messages.danger') }}</strong>
                Please fix the following errors:</p>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    <div class="container chatter_container">

        <div class="columns">

            <aside class="column is-one-third p-5 mt-5">
                <div class="menu">

                    <a href="{{ route('chatter.discussion.create') }}" class="button" id="new_discussion_btn"><i
                            class="chatter-new"></i> New
                        {{ Config::get('chatter.titles.discussion') }}</button>
                        <a class="button is-text" href="/{{ Config::get('chatter.routes.home') }}"><i
                                class="chatter-bubble"></i> All
                            {{ Config::get('chatter.titles.discussions') }}</a>

                        <ul class="mt-4 menu-list">
                            <?php $categories = DevDojo\Chatter\Models\Models::category()->all(); ?>
                            @foreach($categories as $category)
                            <li>
                                <a class="is-flex is-justify-between"
                                    href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.category') }}/{{ $category->slug }}">

                                    {{ $category->name }}
                                    <div class="chatter-box pill is-rounded"
                                        style="background-color:{{ $category->color }}">
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </div>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                </div>
            </aside>


            <div class="column is-two-thirds">
                <div class="panel">
                    <ul class="discussions">
                        @foreach($discussions as $discussion)
                        <li>
                            <a class="discussion_list"
                                href="/{{ Config::get('chatter.routes.home') }}/{{ Config::get('chatter.routes.discussion') }}/{{ $discussion->category->slug }}/{{ $discussion->slug }}">
                                <div class="chatter_avatar">
                                    @if(Config::get('chatter.user.avatar_image_database_field'))

                                    <?php $db_field = Config::get('chatter.user.avatar_image_database_field'); ?>

                                    <!-- If the user db field contains http:// or https:// we don't need to use the relative path to the image assets -->
                                    @if( (substr($discussion->user->{$db_field}, 0, 7) == 'http://') ||
                                    (substr($discussion->user->{$db_field}, 0, 8) == 'https://') )
                                    <img src="{{ $discussion->user->{$db_field}  }}">
                                    @else
                                    <img
                                        src="{{ Config::get('chatter.user.relative_url_to_image_assets') . $discussion->user->{$db_field}  }}">
                                    @endif

                                    @else

                                    <span class="chatter_avatar_circle"
                                        style="background-color:#<?= \DevDojo\Chatter\Helpers\ChatterHelper::stringToColorCode($discussion->user->email) ?>">
                                        {{ strtoupper(substr($discussion->user->email, 0, 1)) }}
                                    </span>

                                    @endif
                                </div>

                                <div class="chatter_middle">
                                    <h3 class="chatter_middle_title">{{ $discussion->title }} <div class="chatter_cat"
                                            style="background-color:{{ $discussion->category->color }}">
                                            {{ $discussion->category->name }}</div>
                                    </h3>
                                    <span class="chatter_middle_details">Posted By: <span
                                            data-href="/user">{{ ucfirst($discussion->user->{Config::get('chatter.user.database_field_with_user_name')}) }}</span>
                                        {{ \Carbon\Carbon::createFromTimeStamp(strtotime($discussion->created_at))->diffForHumans() }}</span>

                                    @if($discussion->post->count())
                                    @if($discussion->post[0]->markdown)
                                    <?php $discussion_body = GrahamCampbell\Markdown\Facades\Markdown::convertToHtml( $discussion->post[0]->body ); ?>
                                    @else
                                    <?php $discussion_body = $discussion->post[0]->body; ?>
                                    @endif

                                    <p>{{ substr(strip_tags($discussion_body), 0, 200) }}@if(strlen(strip_tags($discussion_body))
                                        > 200){{ '...' }}@endif</p>
                                    @endif
                                </div>

                                <div class="chatter_right">

                                    <div class="chatter_count"><i class="chatter-bubble"></i>
                                        {{-- {{ $discussion->postsCount[0]->total }} --}}
                                        POST COUNT
                                    </div>
                                </div>

                                <div class="chatter_clear"></div>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div id="pagination">
                    {{ $discussions->links() }}
                </div>

            </div>
        </div>
    </div>



</div>

<input type="hidden" id="current_path" value="{{ Request::path() }}">

@endsection

@section(Config::get('chatter.yields.footer'))

<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
<script src="/vendor/devdojo/chatter/assets/vendor/spectrum/spectrum.js"></script>
<script src="/vendor/devdojo/chatter/assets/js/chatter.js"></script>
@stop