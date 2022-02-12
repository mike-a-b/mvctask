module.exports.serializeUser = (user) => {
    return {
        id: user._id,
        permission: user.permission,
        username: user.userName,
        email: user.email,
    }
}