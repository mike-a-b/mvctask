'use strict';
module.exports = (sequelize, DataTypes) => {
  const Proxy = sequelize.define('Proxy', {
    port: {
      type: DataTypes.INTEGER
    },
    ip: {
      type: DataTypes.STRING
    },
    type: {
      type: DataTypes.STRING
    },
    special: {
      type: DataTypes.STRING
    },
    status: {
      type: DataTypes.BOOLEAN
    }
  }, {});
  Proxy.createProxy = async (port, ip) => {
    const newP = await Proxy.create({port:port, ip:ip, type:'', special:'', status:false})
    return newP;
  }
  Proxy.getAll = async () => {
    const newP = await Proxy.findAll
  }
  Proxy.associate = function(models) {
    // associations can be defined here
  };
  return Proxy;
};
