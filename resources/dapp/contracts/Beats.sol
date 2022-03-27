// SPDX-License-Identifier: MIT
pragma solidity >=0.4.22 <0.9.0;

contract Beats {
    string public name;
    uint public beatCount = 0;
    mapping(uint => Beat) public beats;

    struct Beat {
        uint id;
        string title;
        uint price;
        address payable owner;
        bool purchased;
    }

    event BeatCreated(
        uint id,
        string title,
        uint price,
        address payable owner,
        bool purchased
    );

    event BeatPurchased(
        uint id,
        string title,
        uint price,
        address payable owner,
        bool purchased
    );

    constructor() {
        name = "Beatchain";
    }

    function createBeat(uint _id, string memory _title, uint _price) public {
        
        require(bytes(_title).length > 0);

        require(_price > 0);
        
        beatCount++;

        beats[beatCount] = Beat(_id, _title, _price, payable(msg.sender), false);

        emit BeatCreated(_id, _title, _price, payable(msg.sender), false);
    }

    function purchaseBeat(uint _id) public payable {
        //fetch product
        
        Beat memory _beat;
        uint i;
        for (i = 1; i <= beatCount; i++) {
            if (beats[i].id == _id) {
                _beat = beats[i];
            }
        }
        

        //fetch address
        address payable _seller = _beat.owner;

        require(_seller != msg.sender);

        // //enough ether
        require(msg.value >= _beat.price);

        _beat.purchased = true;

        //update beat mapping
        beats[i] = Beat(_id, _beat.title, _beat.price, payable(msg.sender), true);
        //beats[_id] = _beat;

        //payment
        payable(address(_seller)).transfer(msg.value);

        emit BeatPurchased(_id, _beat.title, beats[i].price, payable(msg.sender), beats[i].purchased);
    }
}