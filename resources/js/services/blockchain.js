import Web3 from "web3"
import Beats from "../../dapp/build/contracts/Beats.json"

let beats;


export async function loadBlockchainData() {
        const web3 = new Web3(window.ethereum)

        const networkId = await web3.eth.net.getId()

        const networkData = Beats.networks[networkId]

        if (networkData) {
        beats = new web3.eth.Contract(Beats.abi, networkData.address)
        
        } else {
            console.error('Contract not deployed to detected network')
        }

    return beats
}

export function createBeat(contract, id, title, price, owner) {
    price = Web3.utils.toWei(price.toString())
    contract.methods.createBeat(id, title, price).send({ from: owner })
        .once('transactionHash', (transactionHash) => {
            
        })
        .on('error', (error) => {
            
        })
}

export async function getBeatCount(contract) {
    const beatCount = await contract.methods.beatCount().call()
    return beatCount
}

export async function getBeat(contract, id) {
    const beat = await contract.methods.beats(id).call()
    return beat
}

export async function purchaseBeat(contract, id, price, buyer) {
    // , 'Ether'))
    // 
    // 
    const purchase = await contract.methods.purchaseBeat(id).send( { from: buyer, value: Web3.utils.toWei(price.toString(), 'Ether') })
        // .once('receipt', (receipt) => {
        //     // 
        //     // 
        // })

    return purchase
}