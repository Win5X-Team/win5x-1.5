"use strict";

// Class definition
var KTAvatarDemo = function () {
	// Private functions
	var initDemos = function () {
		var avatar1 = new KTAvatar('kt_user_avatar_1');
        var avatar2 = new KTAvatar('kt_user_avatar_2');
        var avatar3 = new KTAvatar('kt_user_avatar_3');
        var avatar4 = new KTAvatar('kt_user_avatar_4');
	}

	return {
		// public functions
		init: function() {
			initDemos();
		}
	};
}();

KTUtil.ready(function() {
	KTAvatarDemo.init();
});
