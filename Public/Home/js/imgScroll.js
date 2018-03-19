/**
 *@author:灏忕櫧
 *@blog:http://www.xiaobai8.com
 *@鐗堟湰:v1.0
 *@des:鏅€氱劍鐐瑰浘,涓昏鏄负浜嗘敮鎸佹墜鏈轰笂闈㈣Е鎽告í鍚戞粴鍔�
 *@鍙互鑷璁剧疆鍙傛暟
 *@渚嬪锛�
 *var test=new mobileImgScroll();
 *test.init({
	Touch:"#J-Touch",
	TouchIco:"#J-Touch-ico",
	Width:320,
	Current:"on",
	isAuto:false,
	offSet:3000
 *});
 *鏆傛椂鍙槸鑷繁鐢ㄨ€屽凡娌¤€冭檻寰堝銆傜鍚堜笅闈㈡垨鑰卍emo鐨勭粨鏋勫氨琛屻€�
 *<ul id="XXXX">
	<li class="XXXX">1</li>
	<li>2</li>
	<li>3</li>
	<li>4</li>
	<li>5</li>
 *</ul>
 */
(function(){
mobileImgScroll=function(){};
mobileImgScroll.prototype={
	/*鍒濆鍖栧璞�*/
	init:function(option){
		if(typeof option == "undefined"){
			option = {};
		}
		/*閭ｄ釜瀵硅薄鍔ㄥ氨璁剧疆鍝釜*/
		this.Touch = option.Touch || "#J-Touch";
		/*閭ｄ釜鏈夋暟瀛楃殑瀵硅薄*/
		this.TouchIco=option.TouchIco || "#J-Touch-ico";
		
		/*姝ゅ弬鏁版渶濂戒笉瑕佷慨鏀�,鐩墠鏈ㄦ湁鑰冭檻閭ｄ箞澶�*/
		this.Pagesize=option.Pagesize|| 1;
		
		/*浣嶇Щ鐨勮窛绂�,鍏跺疄灏辨槸鎸囩殑鏄Щ鍔ㄤ竴涓浘鐗囩殑瀹藉害*/
		this.Width=option.Width || 320;
		/*涓嬮潰鏁板瓧鐨凜urrent鏍峰紡*/
		this.Current=option.Current|| "on";
		
		/*榛樿鏄惁鑷姩杞挱 false*/
		this.isAuto=option.isAuto || false ;		
		this.timer=null;
		this.num=0;		
		if(this.isAuto)
		{
			/*榛樿鏄惁鑷姩杞挱闂撮殧鏃堕棿 3000 杞挱涔嬪悗鍙互璁剧疆,涓嶈疆鎾彲浠ヤ笉鐢ㄨ缃�*/
			this.offSet=option.offSet || 3000;
		}
		this._fnStart();
		this._fnIsAuto();
		
		this._fnTouch(this.Touch.split("#")[1]);
	},
	_fnLength:function(){
		return $(this.Touch).find("li").length;
	},
	_fnMoveWidth:function(){
		return (this.Pagesize)*(this.Width);
	},
	_fnStart:function(){
		var _this=this,
			$ul=$(_this.Touch),
			$ulIco=$(_this.TouchIco),
			_Current=_this.Current,
			_moveWidth=_this._fnMoveWidth();
		/*鍒濆鍖栨粴鍔ㄥ唴瀹圭殑瀹藉害鍜屼綅缃�*/
		$ul.css({"left":0,width:(this._fnLength())*(this.Width)});
		
		$ul.bind("mouseover touchstart",function(){
			clearTimeout(_this.timer);
			_this.timer=null;
		});	
		$ulIco.bind("mouseover touchstart",function(){
			clearTimeout(_this.timer);
			_this.timer=null;
		});		
		$ul.bind("mouseout touchend",function(){
			_this._fnIsAuto();
		});
		$ulIco.delegate("li","click touchstart",function(){
			var _index=$(this).index();
			_this.num=_index;		
			_this._fnAnimate($ul,-(_index*_moveWidth));
			_this._fnAddClass($ulIco.find("li"),_Current,_index);
		});
	},
	_fnAnimate:function(tag,i){
		tag.stop(true,false).animate({"left":i},150);
	},
	_fnAddClass:function(tag,className,i){
		tag.removeClass(className).eq(i).addClass(className);
	},
	/*鏄惁鑷姩杞挱*/
	_fnIsAuto:function(){
		var _this=this;
		if(typeof _this.offSet=="undefined")
		{
			return false;
		}
		if(!_this.timer)
		{
			_this.timer=window.setTimeout(function(){_this._fnAuto();},_this.offSet);
		}
	},
	_fnAuto:function(){
		var _this=this;
		_this.num+=1;
		if(_this.num==_this._fnLength())
		{
			_this.num=0;
		}
		if(_this.num<_this._fnLength())
		{
			_this._fnAnimate($(_this.Touch),-(_this.num*(_this._fnMoveWidth())));
			_this._fnAddClass($(_this.TouchIco).find("li"),_this.Current,_this.num);
		}
		clearTimeout(_this.timer);
		_this.timer=window.setTimeout(function(){_this._fnAuto();},_this.offSet);
	},
	/*杩欓噷鐨勪笅闈㈠叏閮ㄦ槸鎵嬫満涓婇潰鎿嶄綔,姣旇緝绮楃硻涓嶇煡閬撴湁鏈ㄦ湁鏇村ソ鐨勬柟寮忓啓*/
	_fnTouch:function(id){
		var _this=this,
			move=document.getElementById(id);
			_this._StartX=0;
			_this._StratY=0;
			_this._MoveX=0;
			_this._MoveY=0;
			/*璁板綍鎵嬫寚鐐瑰嚮灞忓箷鏃�,灞忓箷杞挱鍥炬鏃剁殑浣嶇疆*/
			_this._temp=0;
			
		move.addEventListener("touchstart",function(e){_this._fnTouchStart(e);}, false);
		move.addEventListener("touchmove",function(e){_this._fnTouchMove(e);}, false);
		move.addEventListener("touchend",function(e){_this._fnTouchEnd(e);}, false);
	},
	/*鎵嬫寚姣忔鐐瑰嚮鍦ㄥ睆骞曚笂鐨勪綅缃甔,Y 澶氱偣瑙﹀睆*/
	_fnTouchX:function(e){
		var touches = e.changedTouches,
		i = 0, l = touches.length, touch,tagX;
		for (; i < l; i++) {
			touch = touches[i];
			tagX=touch.pageX;
		}
		return tagX;
	},
	_fnTouchY:function(e){
		var touches = e.changedTouches,
		i = 0, l = touches.length, touch,tagY;
		for (; i < l; i++) {
			touch = touches[i];
			tagY=touch.pageY;
		}
		return tagY;
	},
	_fnTouchStart:function(e){
		var _this=this;
		_this._StartX=_this._fnTouchX(e);
		_this._StartY=_this._fnTouchY(e);
		
		/*璁板綍鎵嬫寚鐐瑰嚮灞忓箷鏃�,灞忓箷杞挱鍥炬鏃剁殑浣嶇疆 鍦ㄨ繖閲屽垵濮嬪寲*/
		_this._temp=$(_this.Touch).position().left;	
	},
	_fnTouchMove:function(e){
		var _this=this;
		_this._MoveX=_this._fnTouchX(e)-_this._StartX;
		_this._MoveY=_this._fnTouchY(e)-_this._StartY;
		
		/*杩欓噷鏄负浜嗘墜鎸囦竴瀹氭槸妯悜婊氬姩鐨�*/
		if(Math.abs(_this._MoveY)<Math.abs(_this._MoveX))
		{
			e.preventDefault();	
			var moveX=_this._temp+_this._MoveX;
			$(_this.Touch).css({"left":moveX+"px"});
		}		
	},
	_fnTouchEnd:function(e){
		var _this=this;
		/*鎵嬫寚绂诲紑涔嬪悗锛屾墜寮€濮嬪埌缁撴潫鐨勮窛绂�*/
		_this._MoveX=_this._fnTouchX(e)-_this._StartX;
		_this._MoveY=_this._fnTouchY(e)-_this._StartY;
		if(Math.abs(_this._MoveY)<Math.abs(_this._MoveX))
		{
			e.preventDefault();
			/*杩欓噷灏辨槸鏂瑰悜闂鍒ゆ柇 鍚戝彸*/
			if(_this._MoveX>0)
			{	
				_this.num--;
				if(_this.num>=0)
				{
					var moveX=(_this.num)*(_this._fnMoveWidth());
					_this._fnAnimate($(_this.Touch),-moveX);
					_this._fnAddClass($(_this.TouchIco).find("li"),_this.Current,_this.num);
				}
				else
				{
					//缂撳啿鍖哄煙
					this._fnAnimate($(_this.Touch),0);
					_this.num=0;
				}
			}else{
				/*杩欓噷灏辨槸鏂瑰悜闂鍒ゆ柇 鍚戝乏*/
				_this.num++;
				if(_this.num<_this._fnLength() && _this.num>=0)
				{
					var moveX=(_this.num)*(_this._fnMoveWidth());
					_this._fnAnimate($(_this.Touch),-moveX);
					_this._fnAddClass($(_this.TouchIco).find("li"),_this.Current,_this.num);
				}
				else
				{
					//缂撳啿鍖哄煙
					_this.num=_this._fnLength()-1;
					this._fnAnimate($(_this.Touch),-(_this.num*(_this._fnMoveWidth())));
				}
			}
		}		
	}
};	
})(window);