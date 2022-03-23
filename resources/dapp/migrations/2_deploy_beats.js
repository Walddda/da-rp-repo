const Beats = artifacts.require("Beats");

module.exports = function (deployer) {
  deployer.deploy(Beats);
};
