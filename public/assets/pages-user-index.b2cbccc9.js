import{B as e,z as t,s,C as a,y as n,H as l,J as o,o as i,c as u,w as c,n as r,e as d,i as f,a as m,f as g,t as h,b as p,d as k,F as _,g as y,r as x,l as T,k as L}from"./index-b2e89937.js";import{A as v}from"./index.856c5578.js";import{C as A}from"./index.445320ce.js";import{i as C,a as b}from"./user.b238d4cf.js";import{t as j}from"./order.56cadccd.js";import{_ as P}from"./_plugin-vue_export-helper.1b428a4d.js";const z=""+new URL("user-header2-307aec49.png",import.meta.url).href,I=[{id:"all",name:"全部订单",icon:"qpdingdan"},{id:"payment",name:"待支付",icon:"daifukuan",count:0},{id:"delivery",name:"待发货",icon:"daifahuo",count:0},{id:"received",name:"待收货",icon:"daishouhuo",count:0}],D=[{id:"address",name:"收货地址",icon:"shouhuodizhi",type:"link",url:"pages/address/index"},{id:"coupon",name:"领券中心",icon:"lingquan",type:"link",url:"pages/coupon/index",moduleKey:"market-coupon"},{id:"myCoupon",name:"优惠券",icon:"youhuiquan",type:"link",url:"pages/my-coupon/index",moduleKey:"market-coupon"},{id:"refund",name:"退换/售后",icon:"shouhou",type:"link",url:"pages/refund/index",count:0},{id:"contact",name:"在线客服",icon:"kefu",type:"contact"},{id:"points",name:"我的积分",icon:"jifen",type:"link",url:"pages/points/log",moduleKey:"market-points"},{id:"orderCenter",name:"订单中心",icon:"order-c",type:"link",url:"pages/order/center"},{id:"help",name:"我的帮助",icon:"bangzhu",type:"link",url:"pages/help/index",moduleKey:"content-help"}];const S=P({components:{AvatarImage:v,CustomerBtn:A},data:()=>({inArray:e,SettingKeyEnum:t,isLoading:!0,isFirstload:!0,isLogin:!1,setting:{},userInfo:{},assets:{balance:"--",points:"--",coupon:"--"},service:D,orderNavbar:I,todoCounts:{payment:0,deliver:0,received:0}}),onLoad(e){},onShow(e){this.onRefreshPage()},methods:{onRefreshPage(){s(),this.isLogin=a(),this.getPageData()},getPageData(e){const t=this;t.isLoading=!0,Promise.all([t.getSetting(),t.getUserInfo(),t.getUserAssets(),t.getTodoCounts()]).then((s=>{t.isFirstload=!1,t.initService(),t.initOrderTabbar(),e&&e()})).catch((e=>console.log("catch",e))).finally((()=>t.isLoading=!1))},async initService(){const e=this,s=await n.isShowCustomerBtn(),a=[];D.forEach((n=>{n.enabled=!0,"points"===n.id&&(n.name="我的"+e.setting[t.POINTS.value].points_name),"contact"!==n.id||s||(n.enabled=!1),null!=n.count&&(n.count=e.todoCounts[n.id]),a.push(n)})),e.service=l(a)},initOrderTabbar(){const e=this,t=[];I.forEach((s=>{null!=s.count&&(s.count=e.todoCounts[s.id]),t.push(s)})),e.orderNavbar=t},getSetting(){const e=this;return new Promise(((t,s)=>{n.data().then((s=>{e.setting=s,t(s)})).catch(s)}))},getUserInfo(){const e=this;return new Promise(((t,s)=>{e.isLogin?C({},{load:e.isFirstload}).then((s=>{e.userInfo=s.data.userInfo,t(e.userInfo)})).catch((a=>{a.result&&401==a.result.status?(e.isLogin=!1,t(null)):s(a)})):t(null)}))},getUserAssets(){const e=this;return new Promise(((t,s)=>{e.isLogin?b({},{load:e.isFirstload}).then((s=>{e.assets=s.data.assets,t(e.assets)})).catch((a=>{a.result&&401==a.result.status?(e.isLogin=!1,t(null)):s(a)})):t(null)}))},getTodoCounts(){const e=this;return new Promise(((t,s)=>{e.isLogin?j({},{load:e.isFirstload}).then((s=>{e.todoCounts=s.data.counts,t(e.todoCounts)})).catch((a=>{a.result&&401==a.result.status?(e.isLogin=!1,t(null)):s(a)})):t(null)}))},handleLogin(){!this.isLogin&&this.$navTo("pages/login/index")},handleBindMobile(){this.$navTo("pages/user/bind/index")},handlePersonal(){this.$navTo("pages/user/personal/index")},handleLogout(){const e=this;uni.showModal({title:"友情提示",content:"您确定要退出登录吗?",success(t){t.confirm&&o.dispatch("Logout",{}).then((t=>e.onRefreshPage()))}})},onTargetWallet(){this.$navTo("pages/wallet/index")},onTargetOrder(e){this.$navTo("pages/order/index",{dataType:e.id})},onTargetPoints(){this.$navTo("pages/points/log")},onTargetMyCoupon(){this.$navTo("pages/my-coupon/index")},handleService({url:e}){this.$navTo(e)}},onPullDownRefresh(){this.getPageData((()=>{uni.stopPullDownRefresh()}))}},[["render",function(e,t,s,a,n,l){const o=y,v=x("avatar-image"),A=f,C=T,b=x("customer-btn");return n.isFirstload?d("",!0):(i(),u(A,{key:0,class:"container",style:r(e.appThemeStyle)},{default:c((()=>[m(A,{class:"main-header",style:r({height:"H5"==e.platform?"260rpx":"320rpx",paddingTop:"H5"==e.platform?"0":"80rpx"})},{default:c((()=>[m(o,{class:"bg-image",src:z,mode:"scaleToFill"}),n.isLogin?(i(),u(A,{key:0,class:"user-info"},{default:c((()=>[m(A,{class:"user-avatar",onClick:t[0]||(t[0]=e=>l.handlePersonal())},{default:c((()=>[m(v,{url:n.userInfo.avatar_url,width:100},null,8,["url"])])),_:1}),m(A,{class:"user-content"},{default:c((()=>[m(A,{class:"nick-name oneline-hide",onClick:t[1]||(t[1]=e=>l.handlePersonal())},{default:c((()=>[g(h(n.userInfo.nick_name),1)])),_:1}),e.$checkModule("user-grade")&&n.userInfo.grade_id>0&&n.userInfo.grade?(i(),u(A,{key:0,class:"user-grade"},{default:c((()=>[m(A,{class:"user-grade_icon"},{default:c((()=>[m(o,{class:"image",src:"data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAMAAABEpIrGAAAA0lBMVEUAAAD/tjL/tzH/uDP/uC7/tjH/tzH/tzL/tTH+tTL+tjP/tDD/tTD+tzD/tjL/szD/uDH/tjL/tjL+tjD/tjT/szb/tzL/tTL+uTH+tjL/tjL/tjL/tTT/tjL/tjL+tjH/uTL/vDD/tjL/tjH/tzL9uS//tTL/nBr/sS7/tjH/ujL/szD/uTv+rzf/tzL+tzH+vDP+uzL+tjP+ry7+tDL9ki/7szf/sEX/tTL/tjL+tjL/tTH/tTT/tzH/tzL/tjP/sTX/uTP/wzX+rTn/vDX9vC8m8ckhAAAAOXRSTlMAlnAMB/vjxKWGMh0S6drMiVxPRkEY9PLy0ru0sKagmo5+dGtgVCMgBP716eXWyMGxqJGRe2o5KSmFNjaYAAABP0lEQVQ4y8XS13KDMBAF0AWDDe4t7r3ETu9lVxJgJ/n/X8rKAzHG5TE+Twz3zki7I/g/KXdghIbGJewrU4yzn08Ebgl6TuZzzuOC6W5es3HX6qsSz3NFShRU0MpucytDmOSpu3yULx3CA9RD1HjVedc0jSjqm6ZzhUjDsFDQhSp/OKj5GQvg0+ZCOixsbtDLAeTTOm/yGi8GyIphIVsgH737FEDV44LJa88IRKK/SetrwT9G/GUIr6vXjoy4GXn7+RboVXnghuSjaoGecwQxL2su3CwAKlO+QFoqxI4FMctHQhQd2OhxTu184jWUlI+rMTBTn1/IQcJHQ6GQdZ7pWiDaNdhTt330efISeiqYwQEzQpTlsURJLhzkEmpCPsERfeIUVyXr6MNuIyp5uziW6xURtt7hhGwzmMNJExfO4Bd9X0ZPqAxdNwAAAABJRU5ErkJggg=="})])),_:1}),m(A,{class:"user-grade_name"},{default:c((()=>[m(C,null,{default:c((()=>[g(h(n.userInfo.grade.name),1)])),_:1})])),_:1})])),_:1})):(i(),u(A,{key:1,class:"mobile"},{default:c((()=>[g(h(n.userInfo.mobile),1)])),_:1}))])),_:1})])),_:1})):(i(),u(A,{key:1,class:"user-info",onClick:l.handleLogin},{default:c((()=>[m(A,{class:"user-avatar"},{default:c((()=>[m(v,{width:100})])),_:1}),m(A,{class:"user-content"},{default:c((()=>[m(A,{class:"nick-name"},{default:c((()=>[g("未登录")])),_:1}),m(A,{class:"login-tips"},{default:c((()=>[g("点击登录账号")])),_:1})])),_:1})])),_:1},8,["onClick"]))])),_:1},8,["style"]),n.isLogin&&!n.userInfo.mobile&&n.setting[n.SettingKeyEnum.REGISTER.value].isManualBind?(i(),u(A,{key:0,class:"my-mobile",onClick:t[2]||(t[2]=e=>l.handleBindMobile())},{default:c((()=>[m(A,{class:"info"},{default:c((()=>[g("点击绑定手机号，确保账户安全")])),_:1}),m(A,{class:"btn-bind"},{default:c((()=>[g("去绑定")])),_:1})])),_:1})):d("",!0),e.$checkModules(["market-recharge","market-points","market-coupon"])?(i(),u(A,{key:1,class:"my-asset"},{default:c((()=>[m(A,{class:"asset-left flex-box dis-flex flex-x-around"},{default:c((()=>[e.$checkModule("market-recharge")?(i(),u(A,{key:0,class:"asset-left-item",style:{"max-width":"200rpx"},onClick:l.onTargetWallet},{default:c((()=>[m(A,{class:"item-value dis-flex flex-x-center"},{default:c((()=>[m(C,{class:"oneline-hide"},{default:c((()=>[g(h(n.isLogin?n.assets.balance:"--"),1)])),_:1})])),_:1}),m(A,{class:"item-name dis-flex flex-x-center"},{default:c((()=>[m(C,null,{default:c((()=>[g("账户余额")])),_:1})])),_:1})])),_:1},8,["onClick"])):d("",!0),e.$checkModule("market-points")?(i(),u(A,{key:1,class:"asset-left-item",onClick:l.onTargetPoints},{default:c((()=>[m(A,{class:"item-value dis-flex flex-x-center"},{default:c((()=>[m(C,{class:"oneline-hide"},{default:c((()=>[g(h(n.isLogin?n.assets.points:"--"),1)])),_:1})])),_:1}),m(A,{class:"item-name dis-flex flex-x-center"},{default:c((()=>[m(C,null,{default:c((()=>[g(h(n.setting[n.SettingKeyEnum.POINTS.value].points_name),1)])),_:1})])),_:1})])),_:1},8,["onClick"])):d("",!0),e.$checkModule("market-coupon")?(i(),u(A,{key:2,class:"asset-left-item",onClick:l.onTargetMyCoupon},{default:c((()=>[m(A,{class:"item-value dis-flex flex-x-center"},{default:c((()=>[m(C,{class:"oneline-hide"},{default:c((()=>[g(h(n.isLogin?n.assets.coupon:"--"),1)])),_:1})])),_:1}),m(A,{class:"item-name dis-flex flex-x-center"},{default:c((()=>[m(C,null,{default:c((()=>[g("优惠券")])),_:1})])),_:1})])),_:1},8,["onClick"])):d("",!0)])),_:1}),e.$checkModule("market-recharge")?(i(),u(A,{key:0,class:"asset-right"},{default:c((()=>[m(A,{class:"asset-right-item",onClick:l.onTargetWallet},{default:c((()=>[m(A,{class:"item-icon dis-flex flex-x-center"},{default:c((()=>[m(C,{class:"iconfont icon-qianbao"})])),_:1}),m(A,{class:"item-name dis-flex flex-x-center"},{default:c((()=>[m(C,null,{default:c((()=>[g("我的钱包")])),_:1})])),_:1})])),_:1},8,["onClick"])])),_:1})):d("",!0)])),_:1})):d("",!0),m(A,{class:"order-navbar"},{default:c((()=>[(i(!0),p(_,null,k(n.orderNavbar,((e,t)=>(i(),u(A,{class:"order-navbar-item",key:t,onClick:t=>l.onTargetOrder(e)},{default:c((()=>[m(A,{class:"item-icon"},{default:c((()=>[m(C,{class:L(["iconfont",[`icon-${e.icon}`]])},null,8,["class"])])),_:2},1024),m(A,{class:"item-name"},{default:c((()=>[g(h(e.name),1)])),_:2},1024),e.count&&e.count>0?(i(),u(A,{key:0,class:"item-badge"},{default:c((()=>[e.count<=99?(i(),u(C,{key:0,class:"text"},{default:c((()=>[g(h(e.count),1)])),_:2},1024)):(i(),u(C,{key:1,class:"text"},{default:c((()=>[g("99+")])),_:1}))])),_:2},1024)):d("",!0)])),_:2},1032,["onClick"])))),128))])),_:1}),m(A,{class:"my-service"},{default:c((()=>[m(A,{class:"service-title"},{default:c((()=>[g("我的服务")])),_:1}),m(A,{class:"service-content clearfix"},{default:c((()=>[(i(!0),p(_,null,k(n.service,((e,t)=>(i(),p(_,{key:t},["link"==e.type&&e.enabled?(i(),u(A,{key:0,class:"service-item",onClick:t=>l.handleService(e)},{default:c((()=>[m(A,{class:"item-icon"},{default:c((()=>[m(C,{class:L(["iconfont",[`icon-${e.icon}`]])},null,8,["class"])])),_:2},1024),m(A,{class:"item-name"},{default:c((()=>[g(h(e.name),1)])),_:2},1024),e.count&&e.count>0?(i(),u(A,{key:0,class:"item-badge"},{default:c((()=>[e.count<=99?(i(),u(C,{key:0,class:"text"},{default:c((()=>[g(h(e.count),1)])),_:2},1024)):(i(),u(C,{key:1,class:"text"},{default:c((()=>[g("99+")])),_:1}))])),_:2},1024)):d("",!0)])),_:2},1032,["onClick"])):d("",!0),"contact"==e.type&&e.enabled?(i(),u(A,{key:1,class:"service-item"},{default:c((()=>[m(b,null,{default:c((()=>[m(A,{class:"item-icon"},{default:c((()=>[m(C,{class:L(["iconfont",[`icon-${e.icon}`]])},null,8,["class"])])),_:2},1024),m(A,{class:"item-name"},{default:c((()=>[g(h(e.name),1)])),_:2},1024)])),_:2},1024)])),_:2},1024)):d("",!0)],64)))),128))])),_:1})])),_:1}),n.isLogin?(i(),u(A,{key:2,class:"my-logout"},{default:c((()=>[m(A,{class:"logout-btn",onClick:t[3]||(t[3]=e=>l.handleLogout())},{default:c((()=>[m(C,null,{default:c((()=>[g("退出登录")])),_:1})])),_:1})])),_:1})):d("",!0)])),_:1},8,["style"]))}],["__scopeId","data-v-7f7eb825"]]);export{S as default};
