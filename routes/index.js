const express = require('express')
const router = express.Router()
const db = require('../models')

router.get('/profile', auth, async (req, res) => {
    const user = req.user

    res.json({
        ...helper.serializeUser(user),
    })
})

module.exports = router