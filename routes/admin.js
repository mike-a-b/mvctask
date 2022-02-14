const express = require('express')
const router = express.Router()
const path = require('path')
const formidable = require('formidable')
const fs = require('fs')
const User = require('../db').models.user
const Proxy = require('../db').models.Proxy
const Bot = require('../db').models.Bot
const sequelize = require('../db/index')
const Joi = require('joi')
const flash = require('connect-flash')
const session = require('express-session')
const readline = require('readline');
const { ProxyChecker } = require('proxy-checker');
const { Op } = require("sequelize");

function validationProxies(pathtofile) {
    const readInterface = readline.createInterface({
        input: fs.createReadStream(pathtofile),
        output: process.stdout,
        console: false
    });

    readInterface.on('line', async function (line) {
        try {
            let type = '';
            let proxy = line.split(':');

            (async ()=>{
                let pc = new ProxyChecker(proxy[0], proxy[1])
                let result = {};
                result = await pc.check();
                if(result.socks5 == true) type = 'socks5'
                else
                    if(result.socks4 == true) type = 'socks4'
                    else
                        if(result.connect == true) type = 'connect'
                console.log(type);

            })();
            const res = await Proxy.findOne({where :
                    { ip : proxy[0] }
            })

            if(!res)
                if(type)
                {

                    let prxy = await Proxy.createProxy({port:proxy[1], ip:proxy[0], type:type, special:'', status:true});
                } else {
                    let prxy = await Proxy.createProxy({port:proxy[1], ip:proxy[0], type:'', special:'', status:false});
                }

        } catch (err) {
            console.log(err)
        }
    });


}

const validation = (fields, files) => {
    if (files.proxyfile.originalFilename === '' || files.proxyfile.size === 0 ||
        files.proxyfile.mimetype !== 'text/plain') {
        return false;
    }
    return true;
}

const validation2 = (fields, files) => {
    if (files.botsfile.originalFilename === '' || files.botsfile.size === 0 ||
        files.botsfile.mimetype !== 'text/plain') {
        return false;
    }
    return true;
}

router.get('/',  async (req, res, next) => {
    if(req.session.isAuth)
        res.render('admin', {msg: req.flash('msgfile')[0], msg2: req.flash('msgfile2')[0],
        req})
    else
        res.redirect('/login')
})

router.post('/proxy',  async (req, res, next) => {
    let form = new formidable.IncomingForm()
    let upload = path.join('./dist', 'assets', 'upload')
    let fileName = '';

    if (!fs.existsSync(upload)) {
        fs.mkdirSync(upload)
    }
    form.uploadDir = path.join(process.cwd(), upload)
    form.parse(req, function (err, fields, files) {
        if (err) {
            return next(err)
        }
        console.log(fields, files)
        if(files.proxyfile) {
            const valid = validation(fields, files)
            if (!valid) {
                console.log(files.proxyfile.filepath);
                fs.unlinkSync(files.proxyfile.filepath)
                return res.redirect('/admin')
            }
            fileName = path.join(upload,'proxies', files.proxyfile.originalFilename)
            fs.rename(files.proxyfile.filepath, fileName, function (err) {
                if (err) {
                    console.error(err.message)
                    return
                }
                //валидация прокси серверов
                validationProxies(fileName)
            })
        }
    })
    try {
        req.flash('proxycount', await Proxy.Count())
        req.flash('proxywork', await Proxy.Count({
            where: {
                status: true
            }
        }))
        req.flash('proxysocks5', await Proxy.Count({
            where: {
                type: 'socks5'
            }
        }))
        req.flash('proxysocks4', await Proxy.Count({
            where: {
                type: 'socks4'
            }
        }))
        req.flash('proxyhttps', await Proxy.Count({
            where: {
                type: 'connect'
            }
        }))
    } catch (e) {
        console.log(e.message)
    }
    req.flash('msgfile', 'файл с proxy-servers загружен')

    res.redirect('/admin')
})
router.post('/bots',  async (req, res, next) => {
    let form = new formidable.IncomingForm()
    let upload = path.join('./dist', 'assets', 'upload')
    let fileName = '';

    if (!fs.existsSync(upload)) {
        fs.mkdirSync(upload)
    }
    form.uploadDir = path.join(process.cwd(), upload)
    form.parse(req, function (err, fields, files) {
        if (err) {
            return next(err)
        }
        console.log(fields, files)
        if(files.botsfile) {
            const valid = validation2(fields, files)

            if (!valid) {
                console.log(files.botsfile.filepath);
                fs.unlinkSync(files.botsfile.filepath)
                return res.redirect('/admin')
            }a

            const fileName = path.join(upload, 'bots',files.botsfile.originalFilename)

            fs.rename(files.botsfile.filepath, fileName, function (err) {
                if (err) {
                    console.error(err.message)
                    return
                }
                req.flash('msgfile2', 'файл загружен')

                res.redirect('/admin')
            })

        }
    })
    try {
        req.flash('proxycount', await Proxy.Count())
        req.flash('proxywork', await Proxy.Count({
            where: {
                status: true
            }
        }))
        req.flash('proxysocks5', await Proxy.Count({
            where: {
                type: 'socks5'
            }
        }))
        req.flash('proxysocks4', await Proxy.Count({
            where: {
                type: 'socks4'
            }
        }))
        req.flash('proxyhttps', await Proxy.Count({
            where: {
                type: 'connect'
            }
        }))
        console.log(req.flash('proxycount')[0],
            req.flash('proxywork')[0],
            req.flash('proxysocks5')[0],
            req.flash('proxysocks4')[0],
            req.flash('proxyhttps')[0])
    } catch (e) {
        console.log(e.message)
    }
    req.flash('msgfile2', 'файл c ботами загружен')
    res.redirect('/admin')
})


module.exports = router