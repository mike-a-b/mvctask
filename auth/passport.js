const passport = require('passport')
const passportJWT = require('passport-jwt')
const LocalStrategy = require('passport-local').Strategy
const User = require('../db/index')
const SECRET = "opydxnew";
// LocalStrategy
passport.use(
    new LocalStrategy(async function (username, password, done) {
        try {
            const user = await User.getUserByName(username)

            if (!user) {
                return done(null, false)
            }

            if (!User.validPassword(password, username)) {
                return done(null, false)
            }

            return done(null, user)
        } catch (error) {
            done(error)
        }
    })
)

// JWT Strategy
const params = {
    secretOrKey: SECRET,
    jwtFromRequest: function (req) {
        let token = null

        if (req && req.headers) {
            token = req.headers['authorization'] // req.get('authorization')
        }

        return token
    },
}

passport.use(
    new passportJWT.Strategy(params, async function (payload, done) {
        try {
            const user = await User.getUserById(payload.user.id)

            if (!user) {
                return done(new Error('User not found'))
            }

            return done(null, user)

        } catch (error) {
            done(error)
        }
    })
)