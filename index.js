
// ////////////////////////////////////     SWITCHER

const switch1 = document.querySelector("#oneWay")
const switch2 = document.querySelector("#roundTrip")
const switch3 = document.querySelector("#multiCity")
document.querySelector("#form1").classList.remove("show");

function switchingP() {
  if (switch1.checked) {
    // console.log("1")
    document.querySelector("#form1").classList.remove("show");
    document.querySelector("#form2").classList.add("show");
    document.querySelector("#form3").classList.add("show");
  } else if (switch2.checked) {
    // console.log("2")
    document.querySelector("#form1").classList.add("show");
    document.querySelector("#form2").classList.remove("show");
    document.querySelector("#form3").classList.add("show");
  } else if (switch3.checked) {
    // console.log("3")
    document.querySelector("#form1").classList.add("show");
    document.querySelector("#form2").classList.add("show");
    document.querySelector("#form3").classList.remove("show");
  }
}
// ////////////////////////////////////     EXPERIMENTAL

const searchBars = document.querySelectorAll(".airport-search"); 
searchBars.forEach(searchBar => {
  searchBar.addEventListener("input", handleSearch);
});

// Function to handle search bar input
function handleSearch(event) {
  const searchTerm = event.target.value.toLowerCase();
  const matchingAirports = airports.filter(
    airport =>
      airport.code.toLowerCase().startsWith(searchTerm) ||
      airport.city.toLowerCase().startsWith(searchTerm) ||
      airport.country.toLowerCase().startsWith(searchTerm)
  );
  const dropdown = event.target.nextElementSibling;
  dropdown.innerHTML = "";
  if (matchingAirports.length > 0) {
    matchingAirports.forEach(airport => {
      const option = document.createElement("div");
      option.classList.add("airport-option");
      option.innerText = `${airport.code} - ${airport.city}, ${airport.country}`;
      option.addEventListener("click", () => {
        event.target.value = airport.code;
        dropdown.innerHTML = "";
      });
      dropdown.appendChild(option);
    });
  } else {
    const message = document.createElement("div");
    message.innerText = "No matching airports found.";
    dropdown.appendChild(message);
  }
}

// Close dropdowns when user clicks outside of them
document.addEventListener("click", event => {
  const dropdowns = document.querySelectorAll(".airport-dropdown");
  dropdowns.forEach(dropdown => {
    if (!dropdown.contains(event.target)) {
      dropdown.innerHTML = "";
    }
  });
});

// ////////////////////////////////////     SECTION 1 - One Way

const dropdownInput2 = document.querySelector('#PdropdownInput');
const dropdownButton2 = document.querySelector('#PdropdownButton');
const dropdownMenu2 = document.querySelector('.Pdropdown-menu');

dropdownInput2.addEventListener('click', () => {
    dropdownMenu2.classList.toggle('show');
});

dropdownMenu2.addEventListener('mouseleave', () => {
    dropdownMenu2.classList.toggle('show');
});

function getTotalPassengers() {
    const adultsN = document.querySelector('#adultC');
    const childsN = document.querySelector('#childC');
    const infantsN = document.querySelector('#infantC');

    var totalPass = parseInt(adultsN.value) + parseInt(childsN.value) + parseInt(infantsN.value);

    console.log(totalPass);
    if (totalPass > 1) {
        dropdownButton2.innerHTML = totalPass + " Passengers";
    } else {
        dropdownButton2.innerHTML = "1 Passenger";
    }
}

// ////////////////////////////////////     SECTION - Mobile Flight Search
function handleSearchM(event) {
  // console.warn(originalField)
  const searchTermM = event.target.value.toLowerCase();
  const matchingAirportsM = airports.filter(
    airport =>
      airport.code.toLowerCase().startsWith(searchTermM) ||
      airport.city.toLowerCase().startsWith(searchTermM) ||
      airport.country.toLowerCase().startsWith(searchTermM)
  );
  const dropdownM = event.target.parentNode.nextElementSibling;
  // console.log(dropdownM);
  if (dropdownM === null) {
    return;
  }
  dropdownM.innerHTML = "";
  if (matchingAirportsM.length > 0) {
    const listM = document.createElement("ul");
    listM.classList.add("airport-list-m");
    matchingAirportsM.forEach(airport => {
      const listItemM = document.createElement("li");
      listItemM.classList.add("airport-option-m");
      listItemM.innerText = `${airport.code} - ${airport.city}, ${airport.country}`;
      listItemM.addEventListener("click", () => {
        event.target.value = airport.code;
        dropdownM.innerHTML = "";
        // console.warn(event.target.value)
        // console.warn(event.target.parentNode.parentNode.parentNode.previousElementSibling)
        event.target.parentNode.parentNode.parentNode.previousElementSibling.value = event.target.value;
        closeMobileFlightsFromOption(dropdownM);
      });
      listM.appendChild(listItemM);
    });
    dropdownM.appendChild(listM);
  } else {
    const message = document.createElement("div");
    message.innerText = "No matching airports found.";
    dropdownM.appendChild(message);
  }
}
// ////////////////// Close Window on Choosing Option
function closeMobileFlightsFromOption(dropdown) {
  // dropdown in sec2-mob
  // airportDropdownMobile is airport-dropdown-mobile
  const airportDropdownMobile = dropdown.closest(".airport-dropdown-mobile");

  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });
  
  // Remove classes to hide the inner container with slide down effect
  airportDropdownMobile.parentNode.classList.remove('showUp');
  
  // Remove class to hide the black background with fade out effect
  setTimeout(() => {
    airportDropdownMobile.parentNode.classList.remove('show');
    airportDropdownMobile.remove();
  }, 500);
}

function openMobileSearch(field) {
  if (window.innerWidth > window.innerHeight) {return;}

  field.blur();
  const mobileView = `
    <div class="airport-dropdown-mobile">
        <div class="airport-dropdown-mobile-inner">
            <div class="sec1-mob">
                <input type="text" class="airport-search-m" placeholder="${field.placeholder}" oninput="handleSearchM(event)">
                <button class="cancel-flight-search-btn" type="none" onclick="closeMobileFlights(this, event)">X</button>
            </div>
            <div class="sec2-mob">
              <!-- Search result list will be added here -->
            </div>
        </div>
    </div>
  `;
  // console.log(mobileView)
  field.insertAdjacentHTML('afterend', mobileView);
  // Add class to show the black background with fade in effect
  setTimeout(() => {
    document.querySelector('.airport-dropdown-mobile').classList.add('showUp1');
  }, 0);
  // Add class to show the inner container with slide up effect
  setTimeout(() => {
    document.querySelector('.airport-dropdown-mobile-inner').classList.add('showUp');
  }, 0);
  const inputField = document.querySelector(".airport-search-m");
  inputField.focus();
}

function closeMobileFlights(button, event) {
  event.preventDefault();
  const airportDropdownMobile = button.closest(".airport-dropdown-mobile");
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });
  
  // Remove classes to hide the inner container with slide down effect
  airportDropdownMobile.querySelector('.airport-dropdown-mobile-inner').classList.remove('showUp');
  
  // Remove class to hide the black background with fade out effect
  setTimeout(() => {
    airportDropdownMobile.classList.remove('show');
    airportDropdownMobile.remove();
  }, 500);
}

// ////////////////////////////////////     SECTION 3 - Multi Cities

// ///////////////      Add / Remove Cities
function initAirportSearch(input) {
  if (!input) return;

  input.addEventListener("input", handleSearch);

  const dropdown = input.nextElementSibling;
  dropdown.classList.add("airport-dropdown");

  document.addEventListener("click", () => {
    dropdown.innerHTML = "";
  });
}


const addButton = document.querySelector("#addCity");
addButton.addEventListener("click", () => {
  // Create a new set of input fields
  const newCity = document.createElement("div");
  newCity.classList.add("pt32");
  newCity.innerHTML = ` <br>
    <!-- Airports Input Field -->
    <div class="airportInputs d-flex flex-column flex-sm-column flex-md-row">
      <div class="input-group styledInputF">
          <span class="input-group-text" id="inputGroup-sizing-default"><i class="far fa-map-marker-alt"></i></span>
          <input type="text" class="form-control airport-search" placeholder="Flying from" onclick="openMobileSearch(this)" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
          <div class="airport-dropdown"></div>
      </div>
      <div class="input-group styledInputF">
          <span class="input-group-text" id="inputGroup-sizing-default"><i class="far fa-map-marker-alt"></i></span>
          <input type="text" class="form-control airport-search" placeholder="Flying to" onclick="openMobileSearch(this)" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
          <div class="airport-dropdown"></div>
      </div>
    </div>
    <div class="d-flex">
      <!-- DATE Input Field -->
      <div class="input-group styledInputF" id="dateInputGroup">
          <span class="input-group-text" id="inputGroup-sizing-default"><i class="far fa-calendar"></i></span>
          <input placeholder="Date" type="text" class="form-control datepicker" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
      </div>    
      <button type="button" class="removeCity">X</button>
    </div>
  `;

  const [newFromInput, newToInput] = newCity.querySelectorAll(".airport-search");
  initAirportSearch(newFromInput);
  initAirportSearch(newToInput);

  // Add the new set of input fields to the container
  const citiesContainer = document.querySelector(".pt33");
  citiesContainer.appendChild(newCity);

  // Initialize Pikaday for the new datepicker input field
  const newDatepicker = newCity.querySelector(".datepicker");
  const newPicker = new Pikaday({ field: newDatepicker, format: 'D MMM YYYY', minDate: new Date() });

  // Add a click event listener to the remove button
  const removeButton = newCity.querySelector(".removeCity");
  removeButton.addEventListener("click", () => {
    newCity.remove();
  });
});



// /////////////////////////    DATE MANAGEMENT POPUP   /////////////////////////////

function openMobileDatePick(field) {
  if (window.innerWidth > window.innerHeight) {return;}

  field.blur();
  const mobileView = `
    <div class="date-dropdown-mobile">
        <div class="date-dropdown-mobile-inner">
            <div class="sec21-mob">
                <h4 class="p-2">Choose a Date:</h4>
                <button class="cancel-date-btn" type="none" onclick="closeMobileDate(this, event)">X</button>
            </div>
            <div class="sec22-mob">
              <input type="text" id="datepickerFlat" placeholder="Select a date">
            </div>
        </div>
    </div>
  `;
  // console.log(mobileView)
  field.insertAdjacentHTML('afterend', mobileView);
  // Add class to show the black background with fade in effect
  setTimeout(() => {
    document.querySelector('.date-dropdown-mobile').classList.add('showUp3');
  }, 0);
  // Add class to show the inner container with slide up effect
  setTimeout(() => {
    document.querySelector('.date-dropdown-mobile-inner').classList.add('showUp2');
  }, 0);
}

function closeMobileDate(closeBtn, event) {
  event.preventDefault();
  
  const dateDropdownMobile = closeBtn.closest(".date-dropdown-mobile");
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });
  
  // Remove classes to hide the inner container with slide down effect
  dateDropdownMobile.querySelector('.date-dropdown-mobile-inner').classList.remove('showUp2');
  
  // Remove class to hide the black background with fade out effect
  setTimeout(() => {
    dateDropdownMobile.classList.remove('show');
    dateDropdownMobile.remove();
  }, 500);
}