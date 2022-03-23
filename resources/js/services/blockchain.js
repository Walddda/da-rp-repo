import Web3 from "web3"
import Beats from "../../dapp/build/contracts/Beats.json"

let beats;

export async function loadBlockchainData() {
    if (ethereum.selectedAddress) {
        const web3 = new Web3(window.ethereum)

        const networkId = await web3.eth.net.getId()

        const networkData = Beats.networks[networkId]

        if (networkData) {
        beats = new web3.eth.Contract(Beats.abi, networkData.address)
        //console.log(beats, networkId)
        
        // console.log(beatCount.toString())
        //console.log(beats.methods.beats(beatCount).call)
        } else {
            alert('Contract not deployed to detected network')
        }
    } else {
        alert('MetaMask not detected')
    }

    return beats
}

export function createBeat(contract, id, title, price, owner) {
    price = Web3.utils.toWei(price.toString())
    contract.methods.createBeat(id, title, price).send({ from: owner })
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
    //price = Web3.utils.toWei(price, 'ether')
    console.log(Web3.utils.toWei(price.toString(), 'Ether'))
    console.log(id)
    console.log(buyer)
    const purchase = await contract.methods.purchaseBeat(id).send( { from: buyer, value: Web3.utils.toWei(price.toString(), 'Ether') })
        .once('receipt', (receipt) => {
            console.log(receipt.events.BeatPurchased.returnValues)
            console.log(receipt)
            // getBeat(contract, 1)
            //     .then(response => {
            //         console.log(response)
            //     })
        })

    return purchase
}