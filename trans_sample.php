<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script src="./resources/vue%402.js"></script>
	<link rel="stylesheet" href="./resources/materialize.min.css">
	<link rel="stylesheet" href="./resources/material-icons.css" />
	<link rel="stylesheet" href="./resources/prism.min.css" />
	<link rel="stylesheet" href="./resources/main.css?t=2" />
	<link rel="icon" type="image/png" sizes="32x32" href="./resources/favicon-32x32.png">

	
</head>

<body>
	<main id="app">
	<div class="section no-pad-bot center" v-if="loading">
		<div class="container">
			<div class="row">
				<div class="preloader-wrapper active">
				<div class="spinner-layer spinner-blue-only">
					<div class="circle-clipper left">
					<div class="circle"></div>
					</div><div class="gap-patch">
					<div class="circle"></div>
					</div><div class="circle-clipper right">
					<div class="circle"></div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>

	<div v-else-if="error">
		<div class="section no-pad-bot">
			<div class="container">
				<div class="row">
					<div class="col s12 m7">
					<div class="card horizontal">
						<div class="card-stacked">
						<div class="card-content">
							<i class="material-icons">warning</i><p> [[ error ]]</p>
						</div>
						<div class="card-action">
							<a href="#" @click="dismissError">Dismiss</a>
						</div>
						</div>
					</div>
					</div>
					</div>
			</div>
		</div>
	</div>

	<div v-else>
		<div class="section no-pad-bot">
			<div class="container">
				<div class="row">
					<h3 class="header center">Translation API</h3>

					<form class="col s12">
						<div class="row mb-0">
							<div class="col s6 language-select">
								<span>Translate from</span>
								<select class="browser-default" v-model="sourceLang" ref="sourceLangDropdown" @change="handleInput">
									<template v-for="option in langs">
										<option :value="option.code">[[ option.name ]]</option>
									</template>
								</select>
							</div>

							<div class="col s6 language-select">
								<a href="javascript:void(0)" @click="swapLangs" class="btn-switch-language">
									<i class="material-icons">swap_horiz</i>
								</a>
								<span>Translate into</span>
								<select class="browser-default" v-model="targetLang" ref="targetLangDropdown" @change="handleInput">
									<template v-for="option in langs">
										<option v-if="option.code !== 'auto'" :value="option.code">[[ option.name ]]</option>
									</template>
								</select>								
							</div>
						</div>

						<div class="row">
							<div class="input-field textarea-container col s6">
								<label for="textarea1" class="sr-only">
									Text to translate
								</label>								
								<textarea id="textarea1" v-model="inputText" @input="handleInput" ref="inputTextarea"></textarea>
								<button class="btn-delete-text" title="Delete text" @click="deleteText">
									<i class="material-icons">close</i>
								</button>							
								<div class="characters-limit-container" v-if="charactersLimit !== -1">
									<label>[[ inputText.length ]] / [[ charactersLimit ]]</label>
								</div>
							</div>

							<div class="input-field textarea-container col s6">
								<label for="textarea2" class="sr-only">
									Translated text
								</label>									
								<textarea id="textarea2" v-model="translatedText" ref="translatedTextarea" readonly></textarea>
								<button class="btn-copy-translated" @click="copyText">
									<span>[[ copyTextLabel ]]</span> <i class="material-icons">content_copy</i>
								</button>	
								<div class="position-relative">									
									<div class="progress translate" v-if="loadingTranslation">
										<div class="indeterminate"></div>
									</div>																		
								</div>
							</div>
						</div>
					</form>

				</div>
			</div>
		</div>
	</div>
	</main>

	<script src="./resources/materialize.min.js"></script>
	<script>
	// @license magnet:?xt=urn:btih:0b31508aeb0634b347b8270c7bee4d411b5d4109&dn=agpl-3.0.txt AGPL-3.0
	window.Prism = window.Prism || {};
	window.Prism.manual = true;
	// @license-end
	</script>

	<script src="./resources/prism.min.js"></script>

	<script>
	var BaseUrl = "https://translate.argosopentech.com/";

	document.addEventListener('DOMContentLoaded', function(){
		var sidenavElems = document.querySelectorAll('.sidenav');
		var sidenavInstances = M.Sidenav.init(sidenavElems);

		var app = new Vue({
		el: '#app',
		delimiters: ['[[',']]'],
		data: {
			BaseUrl: BaseUrl,
			loading: true,
			error: "",
			langs: [],
			settings: {},
			sourceLang: "",
			targetLang: "",

			loadingTranslation: false,
			inputText: "",
			inputTextareaHeight: 250,
			translatedText: "",
			output: "",
			charactersLimit: -1,

			copyTextLabel: "Copy text"
		},
		mounted: function(){
			var self = this;
			var requestSettings = new XMLHttpRequest();
			requestSettings.open('GET', BaseUrl + '/frontend/settings', true);

			requestSettings.onload = function() {
			if (this.status >= 200 && this.status < 400) {
					// Success!
					self.settings = JSON.parse(this.response);
					self.sourceLang = self.settings.language.source.code;
					self.targetLang = self.settings.language.target.code;
					self.charactersLimit = self.settings.charLimit;
				}else {
					self.error = "Cannot load /frontend/settings";
					self.loading = false;
			}
			};

			requestSettings.onerror = function() {
			self.error = "Error while calling /frontend/settings";
			self.loading = false;
			};

			requestSettings.send();

			var requestLanguages = new XMLHttpRequest();
			requestLanguages.open('GET', BaseUrl + '/languages', true);

			requestLanguages.onload = function() {
			if (this.status >= 200 && this.status < 400) {
				// Success!
				self.langs = JSON.parse(this.response);
				self.langs.push({ name: 'Auto Detect (Experimental)', code: 'auto' })
				if (self.langs.length === 0){
					self.loading = false;
					self.error = "No languages available. Did you install the models correctly?"
					return;
				}

				self.loading = false;
			} else {
				self.error = "Cannot load /languages";
				self.loading = false;
			}
			};

			requestLanguages.onerror = function() {
			self.error = "Error while calling /languages";
			self.loading = false;
			};

			requestLanguages.send();
		},
		updated: function(){
				M.FormSelect.init(this.$refs.sourceLangDropdown);
				M.FormSelect.init(this.$refs.targetLangDropdown);
				if (this.inputText === ""){
					this.$refs.inputTextarea.style.height = this.inputTextareaHeight + "px";
					this.$refs.translatedTextarea.style.height = this.inputTextareaHeight + "px";
				}else{
					this.$refs.inputTextarea.style.height = this.$refs.translatedTextarea.style.height = "1px";
					this.$refs.inputTextarea.style.height = Math.max(this.inputTextareaHeight, this.$refs.inputTextarea.scrollHeight + 32) + "px";
					this.$refs.translatedTextarea.style.height = Math.max(this.inputTextareaHeight, this.$refs.translatedTextarea.scrollHeight + 32) + "px";
				}

				if (this.charactersLimit !== -1 && this.inputText.length >= this.charactersLimit){
					this.inputText = this.inputText.substring(0, this.charactersLimit);
				}
				
				// Update "selected" attribute (to overcome a vue.js limitation)
				// but properly display checkmarks on supported browsers.
				// Also change the <select> width value depending on the <option> length
				for (var i = 0; i < this.$refs.sourceLangDropdown.children.length; i++){
					var el = this.$refs.sourceLangDropdown.children[i];
					if (el.value === this.sourceLang){
						el.setAttribute('selected', '');
						this.$refs.sourceLangDropdown.style.width = getTextWidth(el.text) + 24 + 'px';
					}else{ 
						el.removeAttribute('selected');
					}
				}
				for (var i = 0; i < this.$refs.targetLangDropdown.children.length; i++){
					var el = this.$refs.targetLangDropdown.children[i];
					if (el.value === this.targetLang){
						el.setAttribute('selected', '');
						this.$refs.targetLangDropdown.style.width = getTextWidth(el.text) + 24 + 'px';
					}else{
						el.removeAttribute('selected');
					} 
				}
				
		},
		computed: {
			requestCode: function(){
				return ['const res = await fetch("' + this.BaseUrl + '/translate", {',
	'	method: "POST",',
	'	body: JSON.stringify({',
	'		q: "' + this.$options.filters.escape(this.inputText) + '",',
	'		source: "' + this.$options.filters.escape(this.sourceLang) + '",',
	'		target: "' + this.$options.filters.escape(this.targetLang) + '"',
	'	}),',
	'	headers: { "Content-Type": "application/json" }',
	'});',
	'',
	'console.log(await res.json());'].join("\n");
			}
		},
		filters: {
			escape: function(v){
				return v.replace('"', '\\\"');
			},
			highlight: function(v){
				return Prism.highlight(v, Prism.languages.javascript, 'javascript');
			}
		},
		methods: {
			abortPreviousTransRequest: function(){
				if (this.transRequest){
					this.transRequest.abort();
					this.transRequest = null;
				}
			},
			swapLangs: function(){
				var t = this.sourceLang;
				this.sourceLang = this.targetLang;
				this.targetLang = t;
				this.inputText = this.translatedText;
				this.translatedText = "";
				this.handleInput();
			},
			dismissError: function(){
				this.error = '';
			},
			handleInput: function(e){
				if (this.timeout) clearTimeout(this.timeout);
				this.timeout = null;

				if (this.inputText === ""){
					this.translatedText = "";
					this.output = "";
					this.abortPreviousTransRequest();
					this.loadingTranslation = false;
					return;
				}

				var self = this;

				self.loadingTranslation = true;
				this.timeout = setTimeout(function(){
					self.abortPreviousTransRequest();

					var request = new XMLHttpRequest();
					self.transRequest = request;

					var data = new FormData();
					data.append("q", self.inputText);
					data.append("source", self.sourceLang);
					data.append("target", self.targetLang);
					data.append("api_key", localStorage.getItem("api_key") || "");

					request.open('POST', BaseUrl + '/translate', true);

					request.onload = function() {
					try{
						var res = JSON.parse(this.response);
						// Success!
						if (res.translatedText !== undefined){
							self.translatedText = res.translatedText;
							self.loadingTranslation = false;
							self.output = JSON.stringify(res, null, 4);
						}else{
							throw new Error(res.error || "Unknown error");
						}
					}catch(e){
						self.error = e.message;
						self.loadingTranslation = false;
					}
					};

					request.onerror = function() {
					self.error = "Error while calling /translate";
					self.loadingTranslation = false;
					};

					request.send(data);
				}, '500');
			},
			copyText: function(e){
				e.preventDefault();
				this.$refs.translatedTextarea.select();
				this.$refs.translatedTextarea.setSelectionRange(0, 9999999); /* For mobile devices */
				document.execCommand("copy");

				if (this.copyTextLabel === "Copy text"){
					this.copyTextLabel = "Copied";
					var self = this;
					setTimeout(function(){
						self.copyTextLabel = "Copy text";
					}, 1500);
				}
			},
			deleteText: function(e){
				e.preventDefault();
				this.inputText = this.translatedText = this.output = "";
			}
		}
		});

	});

	function getTextWidth(text) {
		var canvas = getTextWidth.canvas || (getTextWidth.canvas = document.createElement("canvas"));
		var ctx = canvas.getContext("2d");
		ctx.font = 'bold 16px sans-serif';
		var textWidth = Math.ceil(ctx.measureText(text).width);
		return textWidth;
	}	

	function setApiKey(){
		var prevKey = localStorage.getItem("api_key") || "";
		var newKey = "";
		newKey = window.prompt("Type in your API Key. If you need an API key, contact the server operator.", prevKey);
		if (newKey === null) newKey = "";

		localStorage.setItem("api_key", newKey);
	}
	
	// @license-end
	</script>
</body>
</html>