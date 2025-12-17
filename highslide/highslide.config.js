/**
*	Site-specific configuration settings for Highslide JS
*/
hs.graphicsDir = 'highslide/graphics/';
hs.showCredits = false;
hs.outlineType = null;
hs.align = 'center';
hs.blockRightClick = true;
hs.captionEval = 'this.a.title';
hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Затвори"></div>',
	position: 'top right',
	useOnHtml: true,
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});



// Add the slideshow controller
hs.addSlideshow({
	slideshowGroup: 'group1',
	interval: 5000,
	repeat: false,
	useControls: true,
	fixedControls: 'fit',
	overlayOptions: {
		opacity: 0.75,
		position: 'bottom center',
		offsetX: 0,
		offsetY: -10,
		hideOnMouseOut: true
	}
});

// Bulgarian language strings
hs.lang = {
	cssDirection: 'ltr',
	loadingText: 'Зареждане...',
	loadingTitle: 'Натисни за отказ',
	focusTitle: 'Натисни за извеждане отпред',
	fullExpandTitle: 'Разшири до пълен размер (f)',
	creditsText: 'Задвижвано от <i>Highslide JS</i>',
	creditsTitle: 'Отиди на страницата на Highslide JS',
	previousText: 'Предишен',
	nextText: 'Следващ',
	moveText: 'Премести',
	closeText: 'Затвори',
	closeTitle: 'Затвори (esc)',
	resizeTitle: 'Промени размера',
	playText: 'Стартирай',
	playTitle: 'Стартирай галерия (spacebar)',
	pauseText: 'Пауза',
	pauseTitle: 'Пауза на галерия (spacebar)',
	previousTitle: 'Предишен (стрелка наляво)',
	nextTitle: 'Следващ (стрелка надясно)',
	moveTitle: 'Премести',
	fullExpandText: 'Пълен размер',
	number: 'Изображение %1 от %2',
	restoreTitle: 'Натисни за затваряне на изображение, натисни и влачи. Използвай стрелките за следващ и предишен.'
};

// gallery config object
var config1 = {
	slideshowGroup: 'group1',
	transitions: ['expand', 'crossfade']
};
