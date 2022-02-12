const nunjucks = require('nunjucks')
const express = require('express')
const path = require('path')
const app = express()
// const http = require('http')
// const server = http.createServer(app)
const router = express.Router()
const User = require('./db').models.user;
const helper = require('./helper/serialize')
const passport = require('passport')
const tokens = require('./auth/tokens')
const Joi = require('joi')
const nodemailer = require('nodemailer')
const session = require('express-session')
const flash = require('connect-flash')
const cookieParser = require('cookie-parser')
const VKontakteStrategy = require('passport-vkontakte').Strategy;
const config = require('./mailconfig.json')
var captcha = require("nodejs-captcha");
let globValidateMsg = "";
let userr;
passport.use(new VKontakteStrategy({
        clientID:     '8074405',
        clientSecret: 'ab3fVSDlWPOYOUlLGFC7',
        callbackURL:  "http://localhost:3000/auth/vkontakte/callback"
    },
    function(accessToken, refreshToken, params, profile, done) {
        return done(null, profile);
    }
));

app.set("views", path.join(__dirname, "src"))
app.set("view engine", "html")
app.use(cookieParser('keyboard cat'))
app.use(
    session({
        secret: 'keyboard cat',
        key: 'sessionkey',
        cookie: {
            path: '/',
            httpOnly: true,
            maxAge: 10 * 60 * 1000
        },
        saveUninitialized: true,
        resave: false
    })
)
app.use(flash())
app.use(express.json())
app.use(express.urlencoded({ extended: false }))

app.use(express.static(path.join(process.cwd(), 'dist')))
app.use(express.static(path.join(process.cwd(), 'src')))

// require('./db/index')
require('./auth/passport')

const PATH_TO_TEMPLATES = './src/';
nunjucks.configure(PATH_TO_TEMPLATES, {
    autoescape: true,
    express: app
});

app.use((err, _, res, __) => {
    console.log(err.stack)
    res.status(500).json({
        code: 500,
        message: err.message,
    })
})


/****************        ROUTES START          ********************* */
app.get('/', function (req, res, next) {
    if(config.Authorization){
        if(config.Captcha){
            res.redirect('/cap')
        } else
        res.redirect('/login');
    } else {
        req.session.isAuth = true;
        res.redirect('/main');
    }

})
//капча
app.get('/cap', function (req, res, next) {
    var newCaptcha = captcha();
    var v = newCaptcha.value
    let i = newCaptcha.image;
    var w = newCaptcha.width;
    var h = newCaptcha.height;
    // console.log(v,i,w,h);
    res.render('captcha-default', {value:v, image:i, width:w, height:h});
});

app.post('/cap', function (req, res, next) {
    if(req.body.captchavalue === req.body.inputvalue) {
        res.redirect('/login')
    } else {
        let newCaptcha = captcha();
        let v = newCaptcha.value
        let i = newCaptcha.image;
        let w = newCaptcha.width;
        let h = newCaptcha.height;
        res.render('captcha-default', {value:v, image:i, width:w, height:h, msg:'Неправильно введены символы с изображения'});
    }
});

app.get('/auth/vkontakte',
    passport.authenticate('vkontakte'),
    function(req, res){
        // The request will be redirected to vk.com for authentication, so
        // this function will not be called.
});

app.get('/auth/vkontakte/callback',
    passport.authenticate('vkontakte', {
        failureRedirect: '/login',
        session: false
    }),
    function(req, res) {
        req.session.isAuth = true;
        //зарегались через ВК
        res.redirect('/main')
});

app.get('/main', function (req,res, next) {
    if(req.session.isAuth)
    {
        res.render('i', /*{title: 'Главная - '}*/)
    } else {
        res.redirect('/login')
    }
        
})
// app.get('/main2', function (req,res, next) {
//
//         res.render('i2', /*{title: 'Главная - '}*/)
//
// })
// app.get('/main3', function (req,res, next) {
//
//         res.render('i3', /*{title: 'Главная - '}*/)
//
// })
// app.get('/main4', function (req,res, next) {
//
//         res.render('i4', /*{title: 'Главная - '}*/)
//
// })

app.get('/login',  function (req,res, next) {
    if(config.Authorization){
        // res.redirect('/login');
    } else {
        // req.session.isAuth = true;
        res.render('i', /*{title: 'Главная - '}*/)
    }
    if(req.flash('msglogin')[0])//пользгватель успешно создан значит и есть сообщение об этом
    {
        userr = {
            username: req.flash('login')[0],
            password: req.flash('password')[0],
        }

        return res.render('auth-login', {title:'Вход на сайт',
            msglogin:'Пользователь успешно создан!', userr});
    }
    if(req.session.savelogin){
        userr = {
            username: req.session.username,
            password: req.session.password,
        }
        return res.render('auth-login', {title:'Вход на сайт',
            userr, message:req.flash('message')[0], checked:req.session.savelogin});
    } else {
        userr = {
            username: req.body.username,
            password: req.body.password,
        }
        return res.render('auth-login', {title:'Вход на сайт',
            userr, message:req.flash('message')[0], checked:false});
    }

})
//проверка привелегий
const isAuth = async (req, res, next) => {
    
    if(!req.body.username || !req.body.password)
    {
        req.flash('message', 'Не указан логин или пароль');
        req.session.isAuth = false;
        res.redirect('/login')
    }
        
    let user = await User.getUserByName(req.body.username);
    if(!user){
        req.flash('message', 'такого пользователя не существует');
        req.session.isAuth = false;
        res.redirect('/login')
    } else {
        console.log("user.password = ", user.password);
        if (!(user.password === req.body.password)) {
            console.log("user.password = ", user.password, "req.password = ", req.body.password);
            req.flash('message', 'Некорректный логин/пароль');
            req.session.isAuth = false;
            res.redirect('/login');
        }
        req.session.isAuth = true;
        if(req.body.savelogin === "true"){
            req.session.username = user.username;
            req.session.password = user.password;
            req.session.savelogin = true;
            next();
        } else {
            req.session.savelogin = false;
            next();
        }
        next();
    }
    next();
}
//авторизация
app.post('/login', isAuth,  async (req, res, next) => {
    if(req.session.isAuth)
        res.redirect('/main')
    else
        res.redirect('/login')
})

app.post('/refresh-token', async (req, res) => {
    const refreshToken = req.headers['authorization']

    const data = await tokens.refreshTokens(refreshToken)
    res.json({ ...data })
})
/************************************* registration ******************************************/
app.get('/registration', function (req, res, next) {
    console.log("get /registration: ", globValidateMsg);
    userr = {
        username : req.flash('username')[0],
        email:req.flash('email')[0],
        password: req.flash('password')[0],
        password2: req.flash('password2')[0],
    };
    console.log('userr', userr)
    res.render('auth-register', { userr, message:req.flash('msgregistration')[0] });
})

app.post('/registration', async (req, res) => {
    let { username, email, password, password2 } = req.body;
    globValidateMsg = "";
    req.flash('username', username);
    req.flash('email', email);
    req.flash('password', password);
    req.flash('password2', password2);
    try {
        let resultValidateUser = await validateUser(req.body);
        console.log("resultValidateUser", resultValidateUser);
        console.log("globalmsg после валидации", globValidateMsg);
        if(!globValidateMsg) {
            const newUser = await User.createUser(req.body);
            console.log(newUser);
            req.flash('msglogin', 'Пользователь успешно создан!')
            req.flash('login', username);
            req.flash('password', password);
            res.redirect('/login');
        }
        else {
            req.flash('msgregistration', globValidateMsg);
            globValidateMsg = "";
            res.redirect('/registration');
        }
    }
    catch (e)
    {
        console.log("catch e", e);
        req.flash('msgregistration', e.message);
        res.redirect('/registration')
    }
})

app.get('/testAuth', isAuth, async (req, res) => {
    // const user = req.user;
    //
    // res.json({
    //     ...helper.serializeUser(user),
    // })
})


//sadfsafdasdfasdf
/************* VALIDATION INPUT DATA ************************/

const JoiSchema = Joi.object({
    username: Joi.string()
        .alphanum()
        .min(3)
        .max(30)
        .required(),

    password: Joi.string()
        .pattern(new RegExp('^[a-zA-Z0-9]{3,30}$')).max(50).required(),

    password2: Joi.string()
        .pattern(new RegExp('^[a-zA-Z0-9]{3,30}$')).max(50).required(),

    email: Joi.string()
        .email({ minDomainSegments: 2, tlds: { allow: ['com', 'net', 'ru'] } })
})

let validateUser = async ({ username, email, password, password2 }) => new Promise(async (resolve, reject) => {

    const { error } = JoiSchema.validate({username : username, email: email, password: password, password2: password2})
    console.log("const error = validate", error);
    if(error) {
        console.log(error,"это здесь")
        globValidateMsg = error.message;
        reject('Введены некорректные данные '+ error.message );
    } else{
        try {
            if(!(password === password2)) throw new Error('Пароли не совпадают');
            let emailInBase = await User.getUserByEmail(email);
            console.log("emailInBase" . emailInBase);
            if(emailInBase) throw new Error('Такой email уже существует');
            let user = await User.getUserByName(username);
            console.log(user)
            if (user) throw new Error('Пользователь уже существует!');
            resolve("данные корректны");
        }
        catch (err){
            globValidateMsg = err.message;
            reject(globValidateMsg);
        }
    }
}).then(function(result) {
    globValidateMsg = "";

}).catch(function (error){
    // globValidateMsg = error.message;
})
/**************************** восстановление пароля ******************************************/
app.get('/forgotpassword', function (req, res, next) {
    let msg = req.flash('mail')[0];
    console.log("ssadfasfd", msg);
    res.render('auth-forgot-password', {title:'Восстановление пароля', msg});
})

app.post('/forgotpassword', async (req, res, next) => {
    try {
        if (!req.body.email) {
            // если что-либо не указано - сообщаем об этом
            throw new Error('Заполните поле почта!')
        }
        let userInBase = await User.getUserByEmail(req.body.email);
        if (!userInBase) {
            req.flash('mail', "Нет пользователя с таким email");
            res.redirect('/forgotpassword');
        }
        
        const transporter = nodemailer.createTransport(config.mail.smtp);
        const mailOptions = {
            from: `"${config.mail.smtp.auth.user}" <${config.mail.smtp.auth.user}>`,
            to: req.body.email,
            subject: config.mail.subject,
            text:
                "ваш пароль: "+ ' ' + userInBase.password.toString() +
                `\n Отправлено с: <${config.mail.smtp.auth.user}>`,
        }
        // отправляем почту
        transporter.sendMail(mailOptions, function (error, info) {
            // если есть ошибки при отправке - сообщаем об этом
            if (error) {
                console.log(`При отправке письма произошла ошибка!: ${error}`);
                req.flash('mail', `При отправке письма произошла ошибка!: ${error}`)
                res.redirect('/forgotpassword')
            } else {
                console.log('Письмо успешно отправлено!');
                req.flash('mail', 'Письмо успешно отправлено!')
                res.redirect('/forgotpassword');
            }
        })
        req.flash('mail', 'Письмо успешно отправлено!')
    }
    catch (error) {
        console.log(error.message);
        req.flash('mail', error.message);
        res.redirect('/forgotpassword');
    }
    req.flash('mail', 'Письмо успешно отправлено!');
    res.redirect('/forgotpassword');
})
/****админка***/
app.get('/admin',  async (req, res, next) => {
    if(req.session.isAuth)
        res.render('admin')
    else
        res.redirect('/login')
})

module.exports = app;