@extends('admin.layouts')

@section('css')
    <link href="/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/select2/css/select2-bootstrap.min.css" rel="stylesheet" type="text/css" />
@endsection
@section('title', '控制面板')
@section('content')
    <!-- BEGIN CONTENT BODY -->
    <div class="page-content" style="padding-top:0;">
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN PAGE BASE CONTENT -->
                <div class="tab-pane active">
                    <div class="portlet light bordered">
                        <div class="portlet-body form">
                            <!-- BEGIN FORM-->
                            <form action="{{url('admin/editNode')}}" method="post" class="form-horizontal" onsubmit="return do_submit();">
                                <div class="form-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <!-- BEGIN SAMPLE FORM PORTLET-->
                                            <div class="portlet light bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <span class="caption-subject font-dark bold uppercase">基础信息</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="form-group">
                                                        <label for="name" class="col-md-3 control-label"> 节点名称 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="name" value="{{$node->name}}" id="name" placeholder="" autofocus required>
                                                            <input type="hidden" name="id" value="{{$node->id}}">
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="server" class="col-md-3 control-label"> 绑定域名 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="server" value="{{$node->server}}" id="server" placeholder="服务器域名地址，填则优先取域名地址">
                                                            <span class="help-block">如果开启Namesilo且域名是Namesilo上购买的，则会强制更新域名的DNS记录为本节点IP，如果其他节点绑定了该域名则会清空其域名信息</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ssh_port" class="col-md-3 control-label"> SSH端口 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="ssh_port" value="{{$node->ssh_port}}" id="ssh_port" placeholder="服务器SSH端口" required>
                                                            <span class="help-block">请务必正确填写此值，否则TCP阻断检测可能报异常</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ip" class="col-md-3 control-label"> IPV4地址 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="ip" value="{{$node->ip}}" id="ip" placeholder="服务器IPV4地址" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="ipv6" class="col-md-3 control-label"> IPV6地址 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="ipv6" value="{{$node->ipv6}}" id="ipv6" placeholder="服务器IPV6地址，填写则用户可见，域名无效">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status" class="col-md-3 control-label">标签</label>
                                                        <div class="col-md-8">
                                                            <select id="labels" class="form-control select2-multiple" name="labels[]" multiple>
                                                                @foreach($label_list as $label)
                                                                    <option value="{{$label->id}}" @if(in_array($label->id, $node->labels)) selected @endif>{{$label->name}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="group_id" class="col-md-3 control-label"> 所属分组 </label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="group_id" id="group_id">
                                                                <option value="0">请选择</option>
                                                                @if(!$group_list->isEmpty())
                                                                    @foreach($group_list as $group)
                                                                        <option value="{{$group->id}}" {{$node->group_id == $group->id ? 'selected' : ''}}>{{$group->name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                            <span class="help-block">订阅时分组展示</span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="country_code" class="col-md-3 control-label"> 国家/地区 </label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="country_code" id="country_code">
                                                                <option value="">请选择</option>
                                                                @if(!$country_list->isEmpty())
                                                                    @foreach($country_list as $country)
                                                                        <option value="{{$country->country_code}}" {{$node->country_code == $country->country_code ? 'selected' : ''}}>{{$country->country_code}} - {{$country->country_name}}</option>
                                                                    @endforeach
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="desc" class="col-md-3 control-label"> 描述 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="desc" value="{{$node->desc}}" id="desc" placeholder="简单描述">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="sort" class="col-md-3 control-label">排序</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="sort" value="{{$node->sort}}" id="sort" placeholder="">
                                                            <span class="help-block"> 值越大排越前 </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="status" class="col-md-3 control-label">状态</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="status" id="status">
                                                                <option value="1" {{$node->status == '1' ? 'selected' : ''}}>正常</option>
                                                                <option value="0" {{$node->status == '0' ? 'selected' : ''}}>维护</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="is_tcp_check" class="col-md-3 control-label">定时检测</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control" name="is_tcp_check" id="is_tcp_check">
                                                                <option value="1" {{$node->is_tcp_check == '1' ? 'selected' : ''}}>开启</option>
                                                                <option value="0" {{$node->is_tcp_check == '0' ? 'selected' : ''}}>关闭</option>
                                                            </select>
                                                            <span class="help-block"> 启用后会定时检测服务器的连通性 </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="is_subscribe" class="col-md-3 control-label">订阅</label>
                                                        <div class="col-md-8">
                                                            <div class="mt-radio-inline">
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="is_subscribe" value="1" {{$node->is_subscribe ? 'checked' : ''}}> 允许
                                                                    <span></span>
                                                                </label>
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="is_subscribe" value="0" {{!$node->is_subscribe ? 'checked' : ''}}> 不允许
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="traffic_rate" class="col-md-3 control-label"> 流量比例 </label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control" name="traffic_rate" value="{{$node->traffic_rate}}" value="1.0" id="traffic_rate" placeholder="" required>
                                                            <span class="help-block"> 举例：0.1用100M结算10M，5用100M结算500M </span>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="bandwidth" class="col-md-3 control-label">出口带宽</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control" name="bandwidth" value="{{$node->bandwidth}}" id="bandwidth" placeholder="" required>
                                                                <span class="input-group-addon">M</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="traffic" class="col-md-3 control-label">每月可用流量</label>
                                                        <div class="col-md-8">
                                                            <div class="input-group">
                                                                <input type="text" class="form-control right" name="traffic" value="{{$node->traffic}}" id="traffic" placeholder="" required>
                                                                <span class="input-group-addon">G</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label for="monitor_url" class="col-md-3 control-label">监控地址</label>
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control right" name="monitor_url" value="{{$node->monitor_url}}" id="monitor_url" placeholder="节点实时监控地址">
                                                            <span class="help-block"> 例如：http://us1.xxx.com/monitor.php </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END SAMPLE FORM PORTLET-->
                                        </div>
                                        <div class="col-md-6">
                                            <!-- BEGIN SAMPLE FORM PORTLET-->
                                            <div class="portlet light bordered">
                                                <div class="portlet-title">
                                                    <div class="caption">
                                                        <span class="caption-subject font-dark bold">扩展信息</span>
                                                    </div>
                                                </div>
                                                <div class="portlet-body">
                                                    <div class="form-group">
                                                        <label for="service" class="col-md-3 control-label">服务类型</label>
                                                        <div class="col-md-8">
                                                            <div class="mt-radio-inline">
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="service" value="1" @if($node->type == 1) checked @endif> SS/SSR
                                                                    <span></span>
                                                                </label>
                                                                <label class="mt-radio">
                                                                    <input type="radio" name="service" value="2" @if($node->type == 2) checked @endif> V2ray
                                                                    <span></span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <hr />
                                                    <!-- SS/SSR 设置部分 -->
                                                    <div class="ssr-setting {{$node->type == 1 ? '' : 'hidden'}}">
                                                        <div class="form-group">
                                                            <label for="method" class="col-md-3 control-label">加密方式</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="method" id="method">
                                                                    @foreach ($method_list as $method)
                                                                        <option value="{{$method->name}}" @if($method->name == $node->method) selected @endif>{{$method->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="protocol" class="col-md-3 control-label">协议</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="protocol" id="protocol">
                                                                    @foreach ($protocol_list as $protocol)
                                                                        <option value="{{$protocol->name}}" @if($protocol->name == $node->protocol) selected @endif>{{$protocol->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="protocol_param" class="col-md-3 control-label"> 协议参数 </label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="protocol_param" value="{{$node->protocol_param}}" id="protocol_param" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="obfs" class="col-md-3 control-label">混淆</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="obfs" id="obfs">
                                                                    @foreach ($obfs_list as $obfs)
                                                                        <option value="{{$obfs->name}}" @if($obfs->name == $node->obfs) selected @endif>{{$obfs->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="obfs_param" class="col-md-3 control-label"> 混淆参数 </label>
                                                            <div class="col-md-8">
                                                                <textarea class="form-control" rows="5" name="obfs_param" id="obfs_param">{{$node->obfs_param}}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="compatible" class="col-md-3 control-label">兼容SS</label>
                                                            <div class="col-md-8">
                                                                <div class="mt-radio-inline">
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="compatible" value="1" {{$node->compatible == '1' ? 'checked' : ''}}> 是
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="compatible" value="0" {{$node->compatible == '0' ? 'checked' : ''}}> 否
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <span class="help-block"> 如果兼容请在服务端配置协议和混淆时加上<span style="color:red">_compatible</span> </span>
                                                            </div>
                                                        </div>
                                                        <hr />
                                                        <div class="form-group">
                                                            <label for="single" class="col-md-3 control-label">单端口{{$node->single}}</label>
                                                            <div class="col-md-8">
                                                                <div class="mt-radio-inline">
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="single" value="1" {{$node->single ? 'checked' : ''}}> 启用
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="single" value="0" {{!$node->single ? 'checked' : ''}}> 关闭
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                                <span class="help-block"> 如果启用请配置服务端的<span style="color:red"> <a href="javascript:showTnc();">additional_ports</a> </span>信息 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_force" class="col-md-3 control-label">[单] 模式</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="single_force" id="single_force">
                                                                    <option value="0" {{$node->single_force == '0' ? 'selected' : ''}}>兼容模式</option>
                                                                    <option value="1" {{$node->single_force == '1' ? 'selected' : ''}}>严格模式</option>
                                                                </select>
                                                                <span class="help-block"> 严格模式：用户的端口无法连接，只能通过以下指定的端口号进行连接（<a href="javascript:showPortsOnlyConfig();">如何配置</a>）</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_port" class="col-md-3 control-label">[单] 端口号</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="single_port" value="{{$node->single_port}}" id="single_port" placeholder="443">
                                                                <span class="help-block"> 推荐80或443，后端需要配置 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_passwd" class="col-md-3 control-label">[单] 密码</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="single_passwd" value="{{$node->single_passwd}}" id="single_passwd" placeholder="password">
                                                                <span class="help-block"> 展示和生成配置用，后端配置注意保持一致 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_method" class="col-md-3 control-label">[单] 加密方式</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="single_method" id="single_method">
                                                                    @foreach ($method_list as $method)
                                                                        <option value="{{$method->name}}" @if($method->name == $node->single_method) selected @endif>{{$method->name}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <span class="help-block"> 展示和生成配置用，后端配置注意保持一致 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_protocol" class="col-md-3 control-label">[单] 协议</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="single_protocol" id="single_protocol">
                                                                    <option value="origin" {{$node->single_protocol == 'origin' ? 'selected' : ''}}>origin</option>
                                                                    <option value="verify_deflate" {{$node->single_protocol == 'verify_deflate' ? 'selected' : ''}}>verify_deflate</option>
                                                                    <option value="auth_sha1_v4" {{$node->single_protocol == 'auth_sha1_v4' ? 'selected' : ''}}>auth_sha1_v4</option>
                                                                    <option value="auth_aes128_md5" {{$node->single_protocol == 'auth_aes128_md5' ? 'selected' : ''}}>auth_aes128_md5</option>
                                                                    <option value="auth_aes128_sha1" {{$node->single_protocol == 'auth_aes128_sha1' ? 'selected' : ''}}>auth_aes128_sha1</option>
                                                                    <option value="auth_chain_a" {{$node->single_protocol == 'auth_chain_a' ? 'selected' : ''}}>auth_chain_a</option>
                                                                </select>
                                                                <span class="help-block"> 展示和生成配置用，后端配置注意保持一致 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group single-setting {{!$node->single ? 'hidden' : ''}}">
                                                            <label for="single_obfs" class="col-md-3 control-label">[单] 混淆</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="single_obfs" id="single_obfs">
                                                                    <option value="plain" {{$node->single_obfs == 'plain' ? 'selected' : ''}}>plain</option>
                                                                    <option value="http_simple" {{$node->single_obfs == 'http_simple' ? 'selected' : ''}}>http_simple</option>
                                                                    <option value="random_head" {{$node->single_obfs == 'random_head' ? 'selected' : ''}}>random_head</option>
                                                                    <option value="tls1.2_ticket_auth" {{$node->single_obfs == 'tls1.2_ticket_auth' ? 'selected' : ''}}>tls1.2_ticket_auth</option>
                                                                </select>
                                                                <span class="help-block"> 展示和生成配置用，后端配置注意保持一致 </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- V2ray 设置部分 -->
                                                    <div class="v2ray-setting {{$node->type == 2 ? '' : 'hidden'}}">
                                                        <div class="form-group">
                                                            <label for="v2_alter_id" class="col-md-3 control-label">额外ID</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="v2_alter_id" value="{{$node->v2_alter_id}}" id="v2_alter_id" placeholder="16">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_port" class="col-md-3 control-label">端口号</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="v2_port" value="{{$node->v2_port}}" id="v2_port" placeholder="23000">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_net" class="col-md-3 control-label">传输协议</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="v2_net" id="v2_net">
                                                                    <?php $v2_type = ['tcp' => 'TCP', 'kcp' => 'mKCP', 'ws' => 'WebSocket', 'h2' => 'HTTP/2']; ?>
                                                                    @foreach ($v2_type as $k => $v)
                                                                        <option value="{{$k}}" @if($k == $node->v2_net) selected @endif>{{$v}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_type" class="col-md-3 control-label">伪装类型</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="v2_type" id="v2_type">
                                                                    <?php $v2_type = ['none' => '无伪装', 'http' => 'HTTP数据流', 'srtp' => '视频通话数据 (SRTP)', 'utp' => 'BT下载数据 (uTP)', 'wechat-video' => '微信视频通话']; ?>
                                                                    @foreach ($v2_type as $k => $v)
                                                                        <option value="{{$k}}" @if($k == $node->v2_type) selected @endif>{{$v}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_host" class="col-md-3 control-label">伪装域名</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="v2_host" value="{{$node->v2_host}}" id="v2_host">
                                                                <span class="help-block"> 伪装类型为http时多个伪装域名逗号隔开，ws只允许单个 </span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_path" class="col-md-3 control-label">WS/H2路径</label>
                                                            <div class="col-md-8">
                                                                <input type="text" class="form-control" name="v2_path" value="{{$node->v2_path}}" id="v2_path">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="v2_tls" class="col-md-3 control-label">TLS</label>
                                                            <div class="col-md-8">
                                                                <div class="mt-radio-inline">
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="v2_tls" value="1" @if($node->v2_tls == 1) checked @endif> 是
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="v2_tls" value="0" @if($node->v2_tls == 0) checked @endif> 否
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="form-group">
                                                            <label for="v2_ss" class="col-md-3 control-label">SS协议</label>
                                                            <div class="col-md-8">
                                                                <div class="mt-radio-inline">
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="v2_ss" value="1" {{$node->v2_ss ? 'checked' : ''}}> 开启
                                                                        <span></span>
                                                                    </label>
                                                                    <label class="mt-radio">
                                                                        <input type="radio" name="v2_ss" value="0" {{!$node->v2_ss ? 'checked' : ''}}> 关闭
                                                                        <span></span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group v2-ss-setting {{!$node->v2_ss ? 'hidden' : ''}}">
                                                            <label for="v2_ss_method" class="col-md-3 control-label">加密方式</label>
                                                            <div class="col-md-8">
                                                                <select class="form-control" name="v2_ss_method" id="v2_ss_method">
                                                                    <?php $ss_method = ['aes-256-cfb', 'aes-128-cfb', 'chacha20', 'chacha20-ietf', 'aes-256-gcm', 'aes-128-gcm', 'chacha20-poly1305']; ?>
                                                                    @foreach ($ss_method as $method)
                                                                        <option value="{{$method}}" @if($method == $node->v2_ss_method) selected @endif>{{$method}}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- END SAMPLE FORM PORTLET-->
                                        </div>
                                    </div>
                                </div>
                                <div class="form-actions">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn green">提 交</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!-- END FORM-->
                        </div>
                    </div>
                </div>
                <!-- END PAGE BASE CONTENT -->
            </div>
        </div>
    </div>
    <!-- END CONTENT BODY -->
@endsection
@section('script')
    <script src="/assets/global/plugins/select2/js/select2.full.min.js" type="text/javascript"></script>
    <script src="/js/layer/layer.js" type="text/javascript"></script>

    <script type="text/javascript">
        // 用户标签选择器
        $('#labels').select2({
            placeholder: '设置后则可见相同标签的节点',
            allowClear: true
        });

        // ajax同步提交
        function do_submit() {
            var _token = '{{csrf_token()}}';
            var id = '{{Request::get('id')}}';
            var name = $('#name').val();
            var labels = $("#labels").val();
            var group_id = $("#group_id option:selected").val();
            var country_code = $("#country_code option:selected").val();
            var server = $('#server').val();
            var ip = $('#ip').val();
            var ipv6 = $('#ipv6').val();
            var desc = $('#desc').val();
            var method = $('#method').val();
            var traffic_rate = $('#traffic_rate').val();
            var protocol = $('#protocol').val();
            var protocol_param = $('#protocol_param').val();
            var obfs = $('#obfs').val();
            var obfs_param = $('#obfs_param').val();
            var bandwidth = $('#bandwidth').val();
            var traffic = $('#traffic').val();
            var monitor_url = $('#monitor_url').val();
            var is_subscribe = $("input:radio[name='is_subscribe']:checked").val();
            var ssh_port = $('#ssh_port').val();
            var compatible = $("input:radio[name='compatible']:checked").val();
            var single = $('input:radio[name="single"]:checked').val();
            var single_force = $('#single_force').val();
            var single_port = $('#single_port').val();
            var single_passwd = $('#single_passwd').val();
            var single_method = $('#single_method').val();
            var single_protocol = $('#single_protocol').val();
            var single_obfs = $('#single_obfs').val();
            var sort = $('#sort').val();
            var status = $('#status').val();
            var is_tcp_check = $('#is_tcp_check').val();

            var service = $("input:radio[name='service']:checked").val();
            var v2_alter_id = $('#v2_alter_id').val();
            var v2_port = $('#v2_port').val();
            var v2_net = $('#v2_net').val();
            var v2_type = $('#v2_type').val();
            var v2_host = $('#v2_host').val();
            var v2_path = $('#v2_path').val();
            var v2_tls = $("input:radio[name='v2_tls']:checked").val();
            var v2_ss = $("input:radio[name='v2_ss']:checked").val();
            var v2_ss_method = $('#v2_ss_method').val();

            $.ajax({
                type: "POST",
                url: "{{url('admin/editNode')}}",
                async: false,
                data: {
                    _token:_token,
                    id: id,
                    name: name,
                    labels: labels,
                    group_id: group_id,
                    country_code: country_code,
                    server: server,
                    ip: ip,
                    ipv6: ipv6,
                    desc: desc,
                    method: method,
                    traffic_rate: traffic_rate,
                    protocol: protocol,
                    protocol_param: protocol_param,
                    obfs: obfs,
                    obfs_param: obfs_param,
                    bandwidth: bandwidth,
                    traffic: traffic,
                    monitor_url: monitor_url,
                    is_subscribe: is_subscribe,
                    ssh_port: ssh_port,
                    compatible: compatible,
                    single: single,
                    single_force: single_force,
                    single_port: single_port,
                    single_passwd: single_passwd,
                    single_method: single_method,
                    single_protocol: single_protocol,
                    single_obfs: single_obfs,
                    sort: sort,
                    status: status,
                    is_tcp_check: is_tcp_check,
                    type: service,
                    v2_alter_id: v2_alter_id,
                    v2_port: v2_port,
                    v2_net: v2_net,
                    v2_type: v2_type,
                    v2_host: v2_host,
                    v2_path: v2_path,
                    v2_tls: v2_tls,
                    v2_ss: v2_ss,
                    v2_ss_method: v2_ss_method
                },
                dataType: 'json',
                success: function (ret) {
                    layer.msg(ret.message, {time:1000}, function() {
                        if (ret.status == 'success') {
                            window.location.href = '{{url('admin/nodeList?page=') . Request::get('page')}}';
                        }
                    });
                }
            });

            return false;
        }

        function update_ui() {
            var single = parseInt($('input:radio[name="single"]:checked').val());
            var service = parseInt($('input:radio[name="service"]:checked').val());
            var ss = parseInt($('input:radio[name="v2_ss"]:checked').val());
            $(".single-setting").toggleClass('hidden', !single);
            $(".ssr-setting").toggleClass('hidden', service !== 1);
            $(".v2ray-setting").toggleClass('hidden', service !== 2);
            $(".v2-ss-setting").toggleClass('hidden', !ss);
        }

        $('input:radio[name="single"]').on('change', update_ui);
        $("input:radio[name='service']").on('change', update_ui);
        $("input:radio[name='v2_ss']").on('change', update_ui);

        // 服务条款
        function showTnc() {
            var content = '1.请勿直接复制黏贴以下配置，SSR(R)会报错的；'
                + '<br>2.确保服务器时间为CST；'
                + '<br>3.具体请看<a href="https://github.com/ssrpanel/SSRPanel/wiki/%E5%8D%95%E7%AB%AF%E5%8F%A3%E5%A4%9A%E7%94%A8%E6%88%B7%E7%9A%84%E5%9D%91" target="_blank">WIKI</a>；'
                + '<br>'
                + '<br>"additional_ports" : {'
                + '<br>&ensp;&ensp;&ensp;&ensp;"80": {'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"passwd": "password",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"method": "aes-128-ctr",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"protocol": "auth_aes128_md5",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"protocol_param": "#",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"obfs": "tls1.2_ticket_auth",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"obfs_param": ""'
                + '<br>&ensp;&ensp;&ensp;&ensp;},'
                + '<br>&ensp;&ensp;&ensp;&ensp;"443": {'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"passwd": "password",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"method": "aes-128-ctr",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"protocol": "auth_aes128_sha1",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"protocol_param": "#",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"obfs": "tls1.2_ticket_auth",'
                + '<br>&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;&ensp;"obfs_param": ""'
                + '<br>&ensp;&ensp;&ensp;&ensp;}'
                + '<br>},';

            layer.open({
                type: 1
                ,title: '[节点 user-config.json 配置示例]' //不显示标题栏
                ,closeBtn: false
                ,area: '400px;'
                ,shade: 0.8
                ,id: 'tnc' //设定一个id，防止重复弹出
                ,resize: false
                ,btn: ['确定']
                ,btnAlign: 'c'
                ,moveType: 1 //拖拽模式，0或者1
                ,content: '<div style="padding: 20px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">' + content + '</div>'
                ,success: function(layero){
                    //
                }
            });
        }

        // 模式提示
        function showPortsOnlyConfig() {
            var content = '严格模式：'
                + '<br>'
                + '"additional_ports_only": "true"'
                + '<br><br>'
                + '兼容模式：'
                + '<br>'
                + '"additional_ports_only": "false"';

            layer.open({
                type: 1
                ,title: '[节点 user-config.json 配置示例]'
                ,closeBtn: false
                ,area: '400px;'
                ,shade: 0.8
                ,id: 'po-cfg' //设定一个id，防止重复弹出
                ,resize: false
                ,btn: ['确定']
                ,btnAlign: 'c'
                ,moveType: 1 //拖拽模式，0或者1
                ,content: '<div style="padding: 20px; line-height: 22px; background-color: #393D49; color: #fff; font-weight: 300;">' + content + '</div>'
                ,success: function(layero){
                    //
                }
            });
        }
    </script>
@endsection