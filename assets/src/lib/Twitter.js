import codebird from 'codebird'

export default {
  install: function(Vue, ) {
		Object.defineProperty(Vue.prototype, '$cb', {value: new codebird });
	}
}