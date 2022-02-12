'use strict';
module.exports = (sequelize, DataTypes) => {
    const User = sequelize.define('user', {
        username: {
            type: DataTypes.STRING,
            unique: true,
            allowNull: false
        },
        email: {
            type: DataTypes.STRING,
            unique: true,
            allowNull: false
        },
        password: {
            type: DataTypes.STRING,
            allowNull: false,
            set: function (val) {
                this.setDataValue('password', val)
            },
        }
    }, {});
    // для тестирования коннекта и создания модели
    // (async () => {
    //     // Пересоздаем таблицу в БД
    //     await sequelize.sync({ force: true })
    //     // дальнейший код
    // })()
    User.getUserByName = async (userName) => {
        return await User.findOne({where: {username: userName}})
    }
    User.getUserByEmail = async (Email) => {
        return await User.findOne({where: {email: Email}})
    }
    User.getUserById = async (Id) => {
        return await User.findOne({where: {id: id}})
    }
    User.validPassword = async (Password, Username) => {
        user = await User.findOne({where: {username: Username}})
        return (Password === user.password);
        // return bCrypt.compareSync(Password, user.password)
    }
    User.createUser = async (data) => {
        const {email, username, password} = data
        const newUser = await User.create({username: username, email: email, password: password})
        return newUser
    }
    User.associate = function(models) {
        // associations can be defined here
    };
    return User;
}