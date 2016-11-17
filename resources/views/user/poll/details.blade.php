@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div id="voting_wizard" class="col-lg-10 col-lg-offset-1 well wrap-poll">
            <div class="navbar panel">
                <div class="navbar-inner">
                    <div class="col-lg-8 col-lg-offset-2 panel-heading">
                        <ul>
                            <li><a href="#info" data-toggle="tab">{{ trans('polls.nav_tab_edit.info') }}</a></li>
                            <li><a href="#vote" data-toggle="tab">{{ trans('polls.nav_tab_edit.voting') }}</a></li>
                            <li><a href="#result" data-toggle="tab">{{ trans('polls.nav_tab_edit.result') }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="tab-content">
                @include('layouts.message')
                <div class="tab-pane" id="vote">
                {!! Form::open(['route' => 'vote.store','id' => 'form-vote']) !!}
                <!-- VOTE INFO -->
                    @if ((auth()->check() && ! $isUserVoted || !auth()->check() && ! $isParticipantVoted) && ! $isLimit && ! $poll->isClosed())
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="col-lg-8 col-lg-offset-2">
                                    <div class="input-group">
                                        {!! Form::hidden('pollId', $poll->id) !!}
                                        {!! Form::hidden('isRequiredEmail', $isRequiredEmail) !!}
                                        <span class="input-group-addon" id="basic-addon1">
                                            <i class="fa fa-user" aria-hidden="true"></i>
                                        </span>
                                        {!! Form::text('nameVote', auth()->check() ? auth()->user()->name : null, ['class' => 'form-control nameVote', 'placeholder' => trans('polls.placeholder.enter_name')]) !!}
                                        {!! Form::email('emailVote', auth()->check() ? auth()->user()->email : null, ['class' => 'form-control emailVote', 'placeholder' => trans('polls.placeholder.email')]) !!}
                                        <span class="input-group-btn" data-message-email="{{ trans('polls.message_email') }}" data-url="{{ url('/check-email') }}" data-message-required-email="{{ trans('polls.message_required_email') }}" data-message-validate-email="{{ trans('polls.message_validate_email') }}" data-is-required-email="{{ $isRequiredEmail ? 1 : 0 }}">
                                            {{ Form::button(trans('polls.vote'), ['class' => 'btn btn-success btn-vote', !$isUserVoted ? 'disabled' : '']) }}
                                        </span>
                                        @if ($isRequiredEmail)
                                            <label class="label label-warning">required</label>
                                        @endif
                                    </div>
                                    <label class="message-validation"></label>
                                    @if ($isUserVoted)
                                        <label class="show-message">{{ trans('polls.voted_poll') }}</label>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- VOTE OPTION -->
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="tab-content">
                                <!-- VOTE OPTION VERTICAL-->
                                <div id="vertical" class="tab-pane fade in active">
                                    <div class="col-lg-12">
                                        @foreach ($poll->options as $option)
                                            <div class="col-lg-4">
                                                <div class="panel panel-default" id="{{ $option->id }}">
                                                    @if (!$isHideResult || Gate::allows('administer', $poll))
                                                        <div class="panel-heading" style="text-align: center">
                                                            <span class="badge">{{ $option->countVotes() }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="panel-body" style="text-align: center">
                                                        <label style="word-wrap: break-word; text-align: center">{{ $option->name ? $option->name : " " }}</label>
                                                        <img src="{{ $option->showImage() }}" onclick="showModelImage('{{ $option->showImage() }}')" width="60px" height="60px" style="display: block; margin: auto; cursor: pointer">
                                                    </div>
                                                    <div class="panel-footer">
                                                        @if ((auth()->check() && ! $isUserVoted || !auth()->check() && ! $isParticipantVoted) && ! $isLimit && ! $poll->isClosed())
                                                            <center>
                                                                @if ($poll->multiple == trans('polls.label.multiple_choice'))
                                                                    {!! Form::checkbox('option[]', $option->id, false, ['class' => 'poll-option', 'onClick' => 'voted("' . $option->id  .'")', 'id' => 'option-' . $option->id]) !!}
                                                                @else
                                                                    {!! Form::radio('option[]', $option->id, false, ['class' => 'poll-option', 'onClick' => 'voted("' . $option->id  .'")', 'id' => 'option-' . $option->id]) !!}
                                                                @endif
                                                            </center>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- MODAL VIEW IMAGE-->
                    <div id="modalImageOption" class="modal fade" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Image Preview</h4>
                                </div>
                                <div class="modal-body">
                                    <img src="#" id="imageOfOptionPreview">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                </div>
                            </div>

                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="tab-pane" id="info">
                 @if ($isLimit)
                    <label class="alert alert-warning col-lg-6 col-lg-offset-3"> <span class="glyphicon glyphicon-warning-sign"></span> Poll reach limit</label>
                @endif
                    <!-- POLL INFO -->
                    <div class="col-lg-12">
                        <label style="word-wrap: break-word">
                            {{ $poll->title }}
                            <span><a href="#" data-placement="right" data-toggle="tooltip" title="{{ $poll->description }}">
                                <i class="fa fa-info-circle" aria-hidden="true"></i></a>
                        </span>
                        </label>
                        <span>
                        <i class="fa fa-user" aria-hidden="true"></i>
                            @include('user.poll.user_details_layouts', ['user' => $poll->user])
                    </span>
                        <span style="float: right; cursor: pointer" data-placement="top" data-toggle="tooltip" title="{{ $poll->location }}">
                        <i class="fa fa-map-marker" aria-hidden="true"></i> {{ str_limit($poll->location, 20) }}
                    </span>
                    </div>
                    <div class="col-lg-12">
                        <hr style="border: 1px solid white">
                    </div>
                    <!-- COMMENT -->
                    <div class="col-md-12" id="panel-comment">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4> <span class="comment-count">{{ $poll->countComments() }} </span> {{ trans('polls.comments') }}
                                    <span data-label-show-comment = "{{ trans('polls.show_comments') }}" data-label-hide="{{ trans('polls.hide') }}">
                                    <button class="btn btn-warning show" id="show-hide-list-comment">{{ trans('polls.hide') }}</button>
                                </span>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="hide" data-route="{{ url('user/comment') }}" data-confirm-remove="{{ trans('polls.confirmRemove') }}">
                                </div>
                                <div class="comments">
                                    @foreach ($poll->comments as $comment)
                                        <div class="col-md-12" id="{{ $comment->id }}">
                                            <br>
                                            <div class="col-md-2 col-lg-2">
                                                @if (isset($comment->user) && ($comment->name == $comment->user->name))
                                                    <img class="img-comment img-circle" src="{{ $comment->user->getAvatarPath() }}">
                                                @else
                                                    <img class="img-comment img-circle" src="{{ $comment->showDefaultAvatar() }}">
                                                @endif
                                            </div>
                                            <div class="col-md-10 col-lg-10">
                                                <label data-comment-id="{{ $comment->id }}" data-poll-id="{{ $poll->id }}">
                                                    <label class="user-comment">{{ $comment->name }}</label>
                                                    {{ $comment->created_at->diffForHumans() }}
                                                    @if (Gate::allows('ownerPoll', $poll))
                                                        <span class="glyphicon glyphicon-trash delete-comment"></span>
                                                    @endif
                                                </label>
                                                <br>
                                                {{ $comment->content }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="col-lg-12">
                                    <hr style="border: 1px solid darkcyan">
                                </div>
                                <div class="col-lg-12 comment" data-label-add-comment = "{{ trans('polls.add_comment') }}" data-label-hide="{{ trans('polls.hide') }}">
                                    <button class="btn btn-warning show" id="add-comment">{{ trans('polls.hide') }}</button>
                                    {!! Form::open(['route' => 'comment.store', 'class' => 'form-horizontal', 'id' => 'form-comment']) !!}
                                    <div class="col-md-6  comment">
                                        {!! Form::text('name', auth()->check() ? auth()->user()->name : null, ['class' => 'form-control', 'id' => 'name' . $poll->id, 'placeholder' => trans('polls.placeholder.full_name')]) !!}
                                    </div>
                                    <div class="col-md-10 comment" data-poll-id="{{ $poll->id }}" data-user="{{ auth()->check() ? auth()->user()->name : '' }}" data-comment-name="{{ trans('polls.comment_name') }}" data-comment-content="{{ trans('polls.comment_content') }}">
                                        {!! Form::textarea('content', null, ['class' => 'form-control', 'rows' => config('settings.poll.comment_row'), 'placeholder' => trans('polls.placeholder.comment'), 'id' => 'content' . $poll->id]) !!}
                                        <div>
                                            <label class="message-validate comment-name-validate"> </label>
                                        </div>
                                        <div>
                                            <label class="message-validate comment-content-validate"></label>
                                        </div>
                                        {{ Form::button(trans('polls.save_comment'), ['type' => 'submit', 'class' => 'btn btn-primary addComment']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- POLL RESULT -->
                <div class="tab-pane" id="result">
                    @if (!$isHideResult || Gate::allows('administer', $poll))
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <ul class="nav nav-pills">
                                    <li class="active">
                                        <a data-toggle="tab" href="#table">
                                            <i class="fa fa-table" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @if ($optionRateBarChart != "null")
                                    <li>
                                        <a data-toggle="tab" href="#barChart">
                                            <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                    @if ($optionRatePieChart)
                                    <li>
                                        <a data-toggle="tab" href="#pieChart">
                                            <i class="fa fa-pie-chart" aria-hidden="true"></i>
                                        </a>
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="panel-body">
                                <div class="tab-content">
                                    <!-- TABLE RESULT -->
                                    <div id="table" class="tab-pane fade in active">
                                        <table class="table table-hover table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>{{ trans('polls.no') }}</th>
                                                    <th>{{ trans('polls.label.option') }}</th>
                                                    <th>{{ trans('polls.number_vote') }}</th>
                                                    <th>{{ trans('polls.date_last_vote') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            @foreach ($dataTableResult as $key => $data)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>
                                                        <img src="{{ asset($data['image']) }}" width="50px" height="50px">
                                                        {{ $data['name'] }}
                                                    </td>
                                                    <td><span class="badge">{{ $data['numberOfVote'] }}</span></td>
                                                    <td>{{ $data['lastVoteDate'] }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>

                                        <div class="col-lg-12">
                                        <!-- SHOW DETAIL VOTE -->
                                            <div class="col-lg-10">
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
                                                    <span class="glyphicon glyphicon-eye-open"></span>
                                                    {{ trans('polls.show_vote_details') }}
                                                </button>
                                            </div>
                                         </div>

                                        <div class="modal fade" id="myModal" role="dialog">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-body scroll-result">
                                                        @if ($mergedParticipantVotes->count())
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                <th><center>{{ trans('polls.no') }}</center></th>
                                                                <th><center>{{ $isRequiredEmail ? trans('polls.email') : trans('polls.name')}}</center></th>
                                                                @foreach ($poll->options as $option)
                                                                    <th>
                                                                        <center>
                                                                            <img class="img-option" src="{{ $option->showImage() }}">
                                                                            <br>
                                                                            {{ $option->name }}
                                                                        </center>
                                                                    </th>
                                                                @endforeach
                                                                </thead>
                                                                <tbody>
                                                                @foreach ($mergedParticipantVotes as $vote)
                                                                    <tr>
                                                                        <td><center>{{ ++$numberOfVote }}</center></td>
                                                                        @php
                                                                            $isShowVoteName = false;
                                                                        @endphp
                                                                        @foreach ($poll->options as $option)
                                                                            @php
                                                                                $isShowOptionUserVote = false;
                                                                            @endphp
                                                                            @foreach ($vote as $item)
                                                                                @if (! $isShowVoteName)
                                                                                    <td>
                                                                                        @if (isset($item->user_id))
                                                                                            {{ Form::open(['route' => ['vote.destroy', $item->user->id], 'method' => 'delete']) }}
                                                                                            {{ $isRequiredEmail ? $item->user->email : $item->user->name }}
                                                                                        @else
                                                                                            {{ Form::open(['route' => ['vote.destroy', $item->participant->id], 'method' => 'delete']) }}
                                                                                            {{ $isRequiredEmail ? $item->participant->email : $item->participant->name }}
                                                                                        @endif
                                                                                        @if (Gate::allows('administer', $poll))
                                                                                            {{ Form::hidden('poll_id', $poll->id) }}
                                                                                        @endif
                                                                                        {{ Form::close() }}
                                                                                    </td>
                                                                                    @php
                                                                                        $isShowVoteName = true;
                                                                                    @endphp
                                                                                @endif
                                                                                @if ($item->option_id == $option->id)
                                                                                    <td>
                                                                                        <center><label class="label label-default"><span class="glyphicon glyphicon-ok"> </span></label></center>
                                                                                    </td>
                                                                                    @php
                                                                                        $isShowOptionUserVote = true;
                                                                                    @endphp
                                                                                @endif
                                                                            @endforeach
                                                                            @if (!$isShowOptionUserVote)
                                                                                <td></td>
                                                                            @endif
                                                                        @endforeach
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        @else
                                                            <div class="alert alert-info">
                                                                <p>{{ trans('polls.vote_empty') }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('polls.close') }}</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- MODEL VOTE CHART-->
                                    @if ($optionRateBarChart)
                                    <div class="tab-pane fade" id="barChart" role="dialog">
                                        <div class="col-lg-12">
                                            <!-- bar chart -->
                                            <script type="text/javascript" src="http://www.google.com/jsapi"></script>
                                            <script type="text/javascript">
                                                google.load('visualization', '1', {'packages': ['columnchart']});
                                                google.setOnLoadCallback (createChart);
                                                function createChart() {
                                                    var dataTable = new google.visualization.DataTable();
                                                    dataTable.addColumn('string','Quarters 2009');
                                                    dataTable.addColumn('number', 'Earnings');
                                                    var optionRateBarChart = {!! $optionRateBarChart !!};
                                                    dataTable.addRows(optionRateBarChart);
                                                    var chart = new google.visualization.ColumnChart (document.getElementById('chart'));
                                                    var options = {width: 300, height: 440, is3D: false};
                                                    chart.draw(dataTable, options);
                                                }
                                            </script>
                                            <div id="chart"></div>
                                        </div>
                                    </div>
                                    @endif
                                    @if ($optionRateBarChart)
                                    <div class="tab-pane fade" id="pieChart" role="dialog">
                                        <div class="col-lg-12">
                                            <!-- pie chart -->
                                            <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                                            <script type="text/javascript">
                                                google.charts.load('current', {'packages':['corechart']});
                                                google.charts.setOnLoadCallback(drawChart);
                                                function drawChart() {
                                                    // Create the data table.
                                                    var data = new google.visualization.DataTable();
                                                    data.addColumn('string', 'Topping');
                                                    data.addColumn('number', 'Slices');
                                                    var optionRateBarChart = {!! $optionRateBarChart !!};
                                                    data.addRows(optionRateBarChart);
                                                    var options = {'width': 500, 'height': 500};
                                                    var chart = new google.visualization.PieChart(document.getElementById('chart_div'));
                                                    chart.draw(data, options);
                                                }
                                            </script>
                                            <div id="chart_div"></div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            {{ trans('polls.hide_result_message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
