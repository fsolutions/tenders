<?php

namespace App\Http\Controllers\API;

use DateTime;
use Validator;
use App\Model\Boxes;
use App\Model\Platform;
use App\Model\Refollowers;
use Illuminate\Http\Request;
use App\Model\VisitorStatistics;
use App\Bundles\EncrypterStrings;
use App\Bundles\CreateDefaultBoxes;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Model\OpenPlatformStatistics;

class PlatformController extends Controller
{
    public $userId;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {

            if (Auth::check()) {
                $this->userId = Auth::user()->id;
            } else {
                $this->userId = NULL;
            }

            return $next($request);
        });
    }

    /**
     * Display page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('platforms.platforms');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $per_page = empty(request('per_page')) ? 10 : (int) request('per_page');

        if ($this->userId != 1) {
            $platforms = Platform::where("user_id", $this->userId)->latest()->paginate($per_page);
        } else {
            $platforms = Platform::latest()->paginate($per_page);
        }

        $platforms->load(["boxes", "refollowers"]);

        $nowDate = new DateTime();
        foreach ($platforms as $id => $platform) {
            $paidTill = new DateTime($platform->paid_till);
            $intervalDiff = $nowDate->diff($paidTill);
            $platform->is_paied = true;
            if ((int) $intervalDiff->format('%R%a') < 0 || $platform->paid_till == null) {
                $platform->is_paied = false;
            }
        }

        return $platforms;
    }

    /**
     * Creating default boxes for platform
     *
     * @param  int  $platformId
     * @return array
     */
    public function createDefaultBoxes($platformId): array
    {
        $createDefaultBoxes = new CreateDefaultBoxes();
        return $createDefaultBoxes->create($platformId);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $requestData = $request->all();
        $requestData['status'] = 0;
        $requestData['user_id'] = $this->userId;

        $platform = Platform::create($requestData);
        $platform->boxes = $this->createDefaultBoxes($platform->id);

        return $platform;
    }

    /**
     * Display the specified resource page.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showPage($id)
    {
        $platform = Platform::findOrFail($id);

        $platform->load(["boxes", "refollowers"]);

        foreach ($platform->boxes as $box) {
            $box->load(['kupons']);
        }

        $platform->additional_urls = unserialize($platform->additional_urls);
        foreach ($platform->boxes as $keyBox => $box) {
            $kuponSumOfCount = 0;
            $box->nokupons = true;

            foreach ($box->kupons as $keyKupon => $kupon) {
                $kuponSumOfCount += $kupon->count;
            }

            if ($kuponSumOfCount > 0) {
                $box->nokupons = false;
            }
        }

        $nowDate = new DateTime();
        $paidTill = new DateTime($platform->paid_till);
        $intervalDiff = $nowDate->diff($paidTill);
        $platform->is_paied = true;

        if ((int) $intervalDiff->format('%R%a') < 0 || $platform->paid_till == null) {
            $platform->is_paied = false;
        }

        return view('platforms.platform', ['data' => $platform]);
        // return $platform;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $platform = Platform::findOrFail($id);

        $platform->load(["boxes", "refollowers"]);

        return $platform;
        // return response()->json(['data' => $platform], 200);
    }

    /**
     * Open platform statistics
     *
     * @param  int  $platform_id
     * @return \Illuminate\Http\Response
     */
    public function openPlatform($platform_id)
    {
        $openedPlatfrom = OpenPlatformStatistics::create(
            [
                "platform_id" => $platform_id
            ]
        );

        return $openedPlatfrom;
    }

    /**
     * Translate platform to web
     *
     * @param  int  $platform_id
     * @return \Illuminate\Http\Response
     */
    public function webShow($platform_id)
    {
        $refollowerClientCodeId = empty(request('refollowerClientCodeId')) ? -1 : request('refollowerClientCodeId');
        $ref_parent_id = empty(request('refParentId')) ? -1 : request('refParentId');

        $platform = Platform::findOrFail($platform_id);

        $nowDate = new DateTime();
        $paidTill = new DateTime($platform->paid_till);
        $intervalDiff = $nowDate->diff($paidTill);
        if ((int) $intervalDiff->format('%R%a') < 0 || $platform->paid_till == null) {
            return 0;
        }

        $crypter = new EncrypterStrings();

        if ($refollowerClientCodeId != -1) {
            $refollowerClientIdArray = explode("-", $refollowerClientCodeId);
            $userInfo = Refollowers::find($refollowerClientIdArray[0]);
            if ($ref_parent_id != -1) {
                // Раскодируем
                $ref_parent_id = $crypter->decrypt($ref_parent_id);
                VisitorStatistics::create([
                    'platform_id' => $platform_id,
                    'type' => 'withref'
                ]);
            } else {
                // VisitorStatistics::create([
                //     'platform_id' => $platform_id,
                //     'type' => 'noref'
                // ]);
            }
        } else {
            $refollowersController = new RefollowersController();

            if ($ref_parent_id != -1) {
                // Раскодируем
                $ref_parent_id = $crypter->decrypt($ref_parent_id);
                VisitorStatistics::create([
                    'platform_id' => $platform_id,
                    'type' => 'withref'
                ]);
            } else {
                // VisitorStatistics::create([
                //     'platform_id' => $platform_id,
                //     'type' => 'noref'
                // ]);
            }

            $userInfo = $refollowersController->register($platform_id, $ref_parent_id);
        }

        $platform->load(["boxes"]);
        $platform->refClientCode = $userInfo->id . $userInfo->text_id;
        $platform->refClientCodeForLink = $crypter->encrypt($platform->refClientCode);
        $platform->refClientCount = $userInfo->ref_client_count;
        $platform->boxes[0]->kupon = "";
        $platform->boxes[1]->kupon = "";
        $platform->boxes[2]->kupon = "";
        $platform->boxes[3]->kupon = "";

        if ($platform->additional_urls) {
            $platform->additional_urls = unserialize($platform->additional_urls);
        } else {
            $platform->additional_urls = [];
        }

        $platform->makeHidden(["name", "user_id", "can_delete", "paid_till", "created_at", "updated_at", "deleted_at"]);

        $platform->htmlTemplate = '<canvas id="refconfetti"></canvas>
                                    <div id="ref-modal">
                                        <div class="ref-modal-content">
                                            <span class="ref-modal-close">&times;</span>
                                            <div class="ref-container" style="padding-top:25px;">
                                                <div class="ref-row">
                                                    <div class="ref-row-item ref-c6">
                                                        <div class="ref-row-item-card">
                                                            <div class="ref-item-whitebg" style="min-height: 138px;">
                                                                <div class="ref-head">Ваша реферальная ссылка</div>
                                                                <div class="ref-link">{{refLink}}</div>
                                                                <button class="ref-link-copy" id="ref-copy-link">Скопировать ссылку</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="ref-row-item ref-c6">
                                                        <div class="ref-row-item-card">
                                                            <div class="ref-item-whitebg" style="min-height: 138px;">
                                                                <div class="ref-your-login">Ваш логин <span class="ref-your-login-change">(Войти под другим логином)</span></div>
                                                                <div class="ref-row ref-login-row">
                                                                    <div class="ref-row-item ref-c6 ref-item-blue"><div class="ref-icon1 py-10">{{refClientCode}}</div></div>
                                                                    <div class="ref-row-item ref-c6"><div class="ref-icon2 py-10">Запомните ваш логин!</div></div>
                                                                </div>
                                                                <div class="ref-login-note">Для доступа к вашей статистике с другого устройства, введите там логин указанный выше.</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div><!--/ref-row-->
                                                <div class="ref-row">
                                                    <div class="ref-row-item ref-c9">
                                                        <div class="ref-icon3 py-0">Поделитесь ссылкой с вашими друзями! Их переход на сайт будет учтен в вашей статистике. Набрав нужное количество, вам откроется промо-код.</div>
                                                    </div>
                                                    <div class="ref-row-item ref-c3">
                                                        <div class="ref-share-buttons">
                                                            <a href="https://vk.com/share.php?url={{refLink}}&title={{name_visible}}&description={{description}}&noparse=true" target="_blank" class="ref-social" onclick="return Share.me(this);"></a>
                                                            <a href="https://www.facebook.com/sharer/sharer.php?s=100&p%5Btitle%5D={{name_visible}}&p%5Bsummary%5D={{description}}&p%5Burl%5D={{refLink}}" target="_blank" class="ref-social" onclick="return Share.me(this);"></a>
                                                            <a href="https://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1&st.comments={{description}}&st._surl={{refLink}}" target="_blank" class="ref-social" onclick="return Share.me(this);"></a>
                                                            <a href="https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Ffiddle.jshell.net%2F_display%2F&text={{name_visible}}&url={{refLink}}" target="_blank" class="ref-social" onclick="return Share.me(this)"></a>
                                                            <a href="https://api.whatsapp.com/send?text={{name_visible}}%20{{refLink}}" target="_blank" class="ref-social" onclick="return Share.me(this)"></a> 
                                                            <a href="https://t.me/share/url?url={{refLink}}&text={{name_visible}}" target="_blank" class="ref-social" onclick="return Share.me(this)"></a> 
                                                        </div>
                                                    </div>
                                                </div><!--/ref-row-->
                                                <div class="ref-row">
                                                    <div class="ref-row-item ref-c12 ref-row-statistics">
                                                        <div class="ref-head-2">Статистика ваших приглашений</div>
                                                        <div class="ref-client-stat-block">Вы пригласили <span class="ref-client-count">{{refClientCountLine}}</span></div>
                                                    </div>
                                                </div><!--/ref-row-->
                                            </div>
                                            <div class="ref-container">
                                                <div class="ref-line py-5">
                                                    <div class="ref-line-inside">
                                                        {{#boxes}}<div class="ref-line-block ref-c3 ref-line-block-{{index}}"><div class="ref-line-progress" style="width: {{fullfill}}%"></div><div class="ref-line-block-inside">{{grad}}</div></div>{{/boxes}}
                                                        <div class="ref-line-indicator" style="display: none; left: {{indicatorPosition}}%"></div>
                                                    </div>                                                
                                                </div><!--/ref-line-->
                                                <div class="ref-row">
                                                    {{#boxes}}
                                                        <div class="ref-row-item ref-row-item-{{index}} ref-c3 {{additionalClass}}">
                                                            <div class="ref-row-box {{additionalClass}}" id="box-{{id}}">
                                                                <div class="ref-box-not-opened">                                                                
                                                                    <div class="ref-box-image"></div>
                                                                    <div class="ref-box-way">{{way}}</div>
                                                                    <div class="ref-box-way-name">{{way_name}}</div>
                                                                    <div class="ref-box-description">{{way_description}}</div>
                                                                    <div class="ref-box-itog">{{way_name}} <span class="ref-itog-way">{{way}}</span><br>за <span class="ref-blue">{{grad}} друзей</span></div>
                                                                    <button class="ref-box-open">Получить промо-код</button>
                                                                </div><!--/ref-box-not-opened-->
                                                                <div class="ref-box-opened">                                                                
                                                                    <div class="ref-box-image"></div>
                                                                    <div class="ref-box-way">{{way}}</div>
                                                                    <div class="ref-box-way-name">{{way_name}}</div>
                                                                    <div class="ref-box-description-2">Вы можете использовать код при оформлении заказа!</div>
                                                                    <div class="ref-box-kupon"><span class="ref-itog-way ref-bigger-font">{{kupon}}</span></div>
                                                                    <div class="ref-box-restart"><button class="ref-box-restart-btn">Начать заново</button></div>
                                                                </div><!--/ref-box-not-opened-->
                                                            </div>
                                                        </div>
                                                    {{/boxes}}                                                
                                                </div>
                                            </div>
                                            <div class="ref-logo"><a href="https://lk.refollower.ru/" target="_blank"><img src="https://lk.refollower.ru/deliver/img/logo.png" alt="Refollower"></a></div>
                                            <div id="ref-your-login-change-modal">
                                                <div class="ref-your-login-change-modal-content">
                                                    <span class="ref-your-login-change-close">&times;</span>
                                                    <div class="ref-login-h3">Войдите в свой аккаунт</div>
                                                    <input type="text" placeholder="Введите ваш идентификатор" class="ref-input" id="ref-new-account-input" name="ref-new-account-input">
                                                    <div class="ref-login-mini">Например, 1234-ASD-54FG</div>
                                                    <div class="ref-login-message"></div>
                                                    <button class="ref-login-submit" id="ref-change-account-sumbit">Авторизоваться</button>
                                                </div>
                                            </div><!--/ref-your-login-change-modal-->
                                            <div id="ref-box-open-attention-modal">
                                                <div class="ref-box-open-attention-modal-content">
                                                    <span class="ref-box-open-attention-close">&times;</span>
                                                    <div class="ref-login-h3">Вы уверены, что хотите получить промо-код?</div>
                                                    <div class="ref-box-open-p"><strong>Почему мы спрашиваем?</strong></div>
                                                    <div class="ref-box-open-p">По правилам нашей системы, после получения одного из промо-кодов 
                                                                                - теряется прогресс по набранным рефолловерам. 
                                                                                Если вы все еще хотите получить промо-код - нажмите на кнопку ниже.</div>
                                                    <div class="ref-open-box-message"></div>                                                                                
                                                    <div class="ref-open-box-attention-buttons">
                                                        <button class="ref-box-open-attention-submit" id="ref-box-open-attention-submit">Да, хочу!</button>
                                                        <button class="ref-box-open-attention-cancel" id="ref-box-open-attention-cancel">Еще подожду</button>
                                                    </div>
                                                </div>
                                            </div><!--/ref-box-open-attention-modal-->
                                        </div><!--/ref-modal-content-->
                                    </div><!--/ref-modal-->
                                    <div id="ref-bottom">
                                        <div class="ref-bottom-inside">Открой меня</div>
                                    </div>';
        return $platform;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $requestData = $request->all();

        if (isset($requestData['additional_urls'])) {
            $requestData['additional_urls'] = serialize($requestData['additional_urls']);
        }

        $platform = Platform::findOrFail($id);
        $platform->update($requestData);

        return $platform;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $boxes = Boxes::where("platform_id", "=", $id)->get();

        // foreach ($boxes as $box) {
        //     Boxes::destroy($box->id);
        // }

        $platform = Platform::destroy($id);

        return $platform;
    }
}
