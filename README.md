# 我的Pigeon模板
如果你不知道Pigeon是什么，请先前往[github源仓库](https://github.com/kasuganosoras/Pigeon/)看看
## 本模板的修改项：
1. 修改了默认字体
2. 加入了音乐播放的功能(使用[Aplayer](https://github.com/DIYgod/APlayer/))，以及[Meting-js](https://github.com/metowolf/MetingJS)
3. 加入了自定义网页图标
4. 加入了一言
5. 使用国内的Gravatar镜像
6. 加入了看板娘（使用了[live2d.min.js](https://github.com/stevenjoezhang/live2d-widget) 目前用的是公共cdn）
## 效果图
![view1](./.static/view1.png)
![view2](./.static/view2.png)

# 注意事项
1. 原作者的后端并没有提供公告、友链的接口，这个模块是直接改源码加上去的
2. 可以随意命名模板名称，不受限制（最好不要这么做）
3. 若想修改播放列表，请定位到[footer.php](./pigeon/template/{template_name}/footer.php)的最下面，按照格式修改
4. 若想添加歌词，请将歌词放入`./pigeon/template/{template_name}/aplayer.lrc`处，并修改对应代码
5. 自定义字体请放入`./pigeon/main.ttf` 自定义网页图标请放入`./pigeon/favicon.png`
6. 自定义播放列表请修改`<meting-js>`标签中的`id`（歌单id）属性

**Tips:** 本人刚学php以及前端没多久，如有错误还请指出！
