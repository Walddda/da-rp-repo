const Beats = artifacts.require('./Beats.sol')

require('chai')
    .use(require('chai-as-promised'))
    .should()

contract('Beats', ([deployer, seller, buyer]) => {
    let beats

    before(async () => {
        beats = await Beats.deployed()
    })

    describe('deployment', async() => {
        it('deployed successfully', async () => {
            const address = await beats.address
            assert.notEqual(address, 0x0)
        })

        it('correct name', async () => {
            const name = await beats.name()
            assert.equal(name, 'Beatchain')
        })
    })

    

    describe('beats', async() => {
        let result, beatCount

        before(async () => {
            result = await beats.createBeat(83, 'Cool Beat', web3.utils.toWei('1', 'Ether'), { from: seller })
            beatCount = await beats.beatCount()
            
        })

        it('creates Beat', async () => {
            assert.equal(beatCount, 1)
            const event = result.logs[0].args
            console.log(event)
            assert.equal(event.id.toNumber(), 83, 'id is correct')
            assert.equal(event.title, 'Cool Beat', 'name is correct')
            assert.equal(event.price, web3.utils.toWei('1', 'Ether'), 'price is correct')
            assert.equal(event.owner, seller, 'owner is correct')
            assert.equal(event.purchased, false, 'purchased is correct')

            result = await beats.createBeat('', web3.utils.toWei('1', 'Ether'), { from: seller }).should.be.rejected;

            result = await beats.createBeat('Cool Beat', 0, { from: seller }).should.be.rejected;
        })

        it('lists Beats', async () => {
            const beat = await beats.beats(beatCount)
            assert.equal(beat.id.toNumber(), 83, 'id is correct')
            assert.equal(beat.title, 'Cool Beat', 'name is correct')
            assert.equal(beat.price, web3.utils.toWei('1', 'Ether'), 'price is correct')
            assert.equal(beat.owner, seller, 'owner is correct')
            assert.equal(beat.purchased, false, 'purchased is correct')
        })

        it('sells Beats', async () => {

            let oldSellerBalance
            oldSellerBalance = await web3.eth.getBalance(seller)
            oldSellerBalance = new web3.utils.BN(oldSellerBalance)

            purchase = await beats.purchaseBeat(83, { from: buyer, value: web3.utils.toWei('1', 'Ether') })

            const event = purchase.logs[0].args
            console.log(purchase.logs[0].args)
            console.log(beatCount)
            assert.equal(event.id.toNumber(), 83, 'id is correct')
            //assert.equal(event.title, 'Cool Beat', 'name is correct')
            assert.equal(event.price, web3.utils.toWei('1', 'Ether'), 'price is correct')
            assert.equal(event.owner, buyer, 'owner is correct')
            assert.equal(event.purchased, true, 'purchased is correct')

            let newSellerBalance
            newSellerBalance = await web3.eth.getBalance(seller)
            newSellerBalance = new web3.utils.BN(newSellerBalance)

            let price
            price = web3.utils.toWei('1', 'Ether')
            price = new web3.utils.BN(price)

            const excpectedBalance = oldSellerBalance.add(price)

            assert.equal(newSellerBalance.toString(), excpectedBalance.toString())

            //console.log(oldSellerBalance, newSellerBalance, price)

            await beats.purchaseBeat(24, { from: buyer, value: web3.utils.toWei('1', 'Ether') }).should.be.rejected

            await beats.purchaseBeat(83, { from: buyer, value: web3.utils.toWei('0.5', 'Ether') }).should.be.rejected

            await beats.purchaseBeat(83, { from: seller, value: web3.utils.toWei('1', 'Ether') }).should.be.rejected

            // await beats.purchaseBeat(23, { from: deployer, value: web3.utils.toWei('1', 'Ether') }).should.be.rejected
        })
    })
})