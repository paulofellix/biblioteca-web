import mitt from 'mitt';

let emitter = mitt();

export default function (app, options) {
  app.config.globalProperties.$emitter = emitter;
}