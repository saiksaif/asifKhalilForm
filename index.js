// ////////////////////////////////////     SWITCHER

const switch1 = document.querySelector("#oneWay");
const switch2 = document.querySelector("#roundTrip");
const switch3 = document.querySelector("#multiCity");
document.querySelector("#form1").classList.remove("show");

function switchingP() {
  if (switch1.checked) {
    // console.log("1")
    document.querySelector("#form1").classList.remove("show");
    document.querySelector("#oneWayOptions").classList.remove("col-sm-6");
    document.querySelector("#oneWayOptions").classList.add("col-sm-12");
    document.querySelector("#roundTripOption").classList.add("show");
    document.querySelector("#form3").classList.add("show");
  } else if (switch2.checked) {
    // console.log("2")
    document.querySelector("#form1").classList.remove("show");
    document.querySelector("#oneWayOptions").classList.add("col-sm-6");
    document.querySelector("#oneWayOptions").classList.remove("col-sm-12");
    document.querySelector("#roundTripOption").classList.remove("show");
    document.querySelector("#form3").classList.add("show");
  } else if (switch3.checked) {
    // console.log("3")
    document.querySelector("#form1").classList.add("show");
    document.querySelector("#oneWayOptions").classList.add("col-sm-6");
    document.querySelector("#oneWayOptions").classList.remove("col-sm-12");
    document.querySelector("#roundTripOption").classList.add("show");
    document.querySelector("#form3").classList.remove("show");
  }
}
switchingP();
// ////////////////////////////////////     EXPERIMENTAL

const searchBars = document.querySelectorAll(".airport-search");
searchBars.forEach((searchBar) => {
  searchBar.addEventListener("input", handleSearch);
});

// Function to handle search bar input
function handleSearch(event) {
  const searchTerm = event.target.value.toLowerCase();
  const matchingAirports = airports.filter(
    (airport) =>
      airport.code.toLowerCase().startsWith(searchTerm) ||
      airport.city.toLowerCase().startsWith(searchTerm) ||
      airport.country.toLowerCase().startsWith(searchTerm)
  );
  const dropdown = event.target.nextElementSibling;
  dropdown.innerHTML = "";
  if (matchingAirports.length > 0) {
    matchingAirports.forEach((airport) => {
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
document.addEventListener("click", (event) => {
  const dropdowns = document.querySelectorAll(".airport-dropdown");
  dropdowns.forEach((dropdown) => {
    if (!dropdown.contains(event.target)) {
      dropdown.innerHTML = "";
    }
  });
});

// ////////////////////////////////////     SECTION - Mobile Flight Search
function handleSearchM(event) {
  // console.warn(originalField)
  const searchTermM = event.target.value.toLowerCase();
  const matchingAirportsM = airports.filter(
    (airport) =>
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
    matchingAirportsM.forEach((airport) => {
      const listItemM = document.createElement("li");
      // listItemM.parentElement.classList.add("airport-option-parent");
      listItemM.classList.add("airport-option-m");
      listItemM.innerHTML = `<strong>${airport.code}</strong>  -  ${airport.city}, ${airport.country}`;
      listItemM.addEventListener("click", () => {
        event.target.value = airport.code;
        dropdownM.innerHTML = "";
        // console.warn(event.target.value)
        // console.warn(event.target.parentNode.parentNode.parentNode.previousElementSibling)
        event.target.parentNode.parentNode.parentNode.previousElementSibling.value =
          event.target.value;
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
  airportDropdownMobile.parentNode.classList.remove("showUp");

  // Remove class to hide the black background with fade out effect
  setTimeout(() => {
    airportDropdownMobile.parentNode.classList.remove("show");
    airportDropdownMobile.remove();
  }, 500);
}

function openMobileSearch(field) {
  if (window.innerWidth > window.innerHeight) {
    return;
  }

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
  field.insertAdjacentHTML("afterend", mobileView);
  // Add class to show the black background with fade in effect
  setTimeout(() => {
    document.querySelector(".airport-dropdown-mobile").classList.add("showUp1");
  }, 0);
  // Add class to show the inner container with slide up effect
  setTimeout(() => {
    document
      .querySelector(".airport-dropdown-mobile-inner")
      .classList.add("showUp");
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
  airportDropdownMobile
    .querySelector(".airport-dropdown-mobile-inner")
    .classList.remove("showUp");

  // Remove class to hide the black background with fade out effect
  setTimeout(() => {
    airportDropdownMobile.classList.remove("show");
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
          <input placeholder="Date" type="text" class="form-control datepicker" onclick="openMobileDatePick(this)" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" required>
      </div>    
      <button type="button" class="removeCity">X</button>
    </div>
  `;

  const [newFromInput, newToInput] =
    newCity.querySelectorAll(".airport-search");
  initAirportSearch(newFromInput);
  initAirportSearch(newToInput);

  // Add the new set of input fields to the container
  const citiesContainer = document.querySelector(".pt33");
  citiesContainer.appendChild(newCity);

  // Initialize Pikaday for the new datepicker input field
  const newDatepicker = newCity.querySelector(".datepicker");
  const newPicker = new Pikaday({
    field: newDatepicker,
    format: "D MMM YYYY",
    minDate: new Date(),
  });

  // Add a click event listener to the remove button
  const removeButton = newCity.querySelector(".removeCity");
  removeButton.addEventListener("click", () => {
    newCity.remove();
  });
});

// /////////////////////////    DATE MANAGEMENT POPUP   /////////////////////////////

function openMobileDatePick(field) {
  if (window.innerWidth > window.innerHeight) {
    return;
  }

  field.blur();
  const mobileView = `
    <div class="date-dropdown-mobile">
        <div class="date-dropdown-mobile-inner">
            <div class="sec21-mob">
                <h4 class="p-2">Choose a Date:</h4>
                <button class="cancel-date-btn" type="none" onclick="closeMobileDate(this, event)">X</button>
            </div>
            <div class="sec22-mob">
              <form action="" class="row justify-content-around">
                <div class="col-md-12">
                  <div id="inline_cal" style="width:fit-content; margin: 0 auto"></div>
                </div>
              </form>
            </div>
        </div>
    </div>
  `;
  romeCalInitializer();

  field.insertAdjacentHTML("afterend", mobileView);

  setTimeout(() => {
    document.querySelector(".date-dropdown-mobile").classList.add("showUp3");
  }, 0);

  setTimeout(() => {
    document
      .querySelector(".date-dropdown-mobile-inner")
      .classList.add("showUp2");
  }, 0);
}

// Function for assigning chosen date to closest input field
function mobCalChoosen(date) {
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });

  const dateDropdownMobile = document
    .querySelector(".cancel-date-btn")
    .closest(".date-dropdown-mobile");
  // dateDropdownMobile.querySelector('.date-dropdown-mobile-inner').classList.remove('showUp2');

  // setTimeout(() => {
  //   dateDropdownMobile.classList.remove('show');
  //   dateDropdownMobile.remove();
  // }, 500);

  const dateField = document.querySelector(
    ".date-dropdown-mobile"
  ).previousElementSibling;
  dateField.value = date;
  dateField.blur();
}

// Function for the close button in date popup
function closeMobileDate(closeBtn, event) {
  event.preventDefault();

  const dateDropdownMobile = closeBtn.closest(".date-dropdown-mobile");
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });

  dateDropdownMobile
    .querySelector(".date-dropdown-mobile-inner")
    .classList.remove("showUp2");

  setTimeout(() => {
    dateDropdownMobile.classList.remove("show");
    dateDropdownMobile.remove();
  }, 500);
}

// /////////////////////////    SEATS MANAGEMENT POPUP   /////////////////////////////

function openMobileSeatsManager(field) {
  if (window.innerWidth > window.innerHeight) {
    return;
  }

  field.blur();
  const mobileView = `
    <div class="seats-dropdown-mobile">
        <div class="seats-dropdown-mobile-inner">
            <div class="sec21-mob">
                <h4 class="p-2">Choose Seats:</h4>
                <button class="cancel-seats-btn" type="none" onclick="closeMobileSeats(this, event)">X</button>
            </div>
            <div class="sec22-mob-3">
            <h4>Passengers:</h4>
              <div class="d-flex justify-content-between m-1">
                  <label for="adults">
                      Adults:
                  </label>
                  <div class="numberControls d-flex">
                      <button onclick="passengerInc(event, 6)">+</button>
                      <p class="passengerThis">1</p>
                      <button onclick="passengerDec(event, 1)">-</button>
                  </div>
              </div>
              <div class="d-flex justify-content-between m-1">
                  <label for="adults">
                      Children:
                  </label>
                  <div class="numberControls d-flex">
                      <button onclick="passengerInc(event, 5)">+</button>
                      <p class="passengerThis">0</p>
                      <button onclick="passengerDec(event, 0)">-</button>
                  </div>
              </div>
              <div class="d-flex justify-content-between m-1">
                  <label for="adults">
                      Infants:
                  </label>
                  <div class="numberControls d-flex">
                      <button onclick="passengerInc(event, 2)">+</button>
                      <p class="passengerThis">0</p>
                      <button onclick="passengerDec(event, 0)">-</button>
                  </div>
              </div>
            </div>
        </div>
    </div>
  `;
  // romeCalInitializer()

  field.insertAdjacentHTML("afterend", mobileView);

  setTimeout(() => {
    document.querySelector(".seats-dropdown-mobile").classList.add("showUp5");
  }, 0);

  setTimeout(() => {
    document
      .querySelector(".seats-dropdown-mobile-inner")
      .classList.add("showUp4");
  }, 0);
}

// Function for assigning chosen date to closest input field

function mobSeatsChoosen(date) {
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });

  const dateDropdownMobile = document
    .querySelector(".cancel-seats-btn")
    .closest(".seats-dropdown-mobile");
  dateDropdownMobile
    .querySelector(".seats-dropdown-mobile-inner")
    .classList.remove("showUp4");

  setTimeout(() => {
    dateDropdownMobile.classList.remove("show");
    dateDropdownMobile.remove();
  }, 500);

  const dateField = document.querySelector(
    ".seats-dropdown-mobile"
  ).previousElementSibling;
  dateField.value = date;
  dateField.blur();
}

// Function for the close button in date popup
function closeMobileSeats(closeBtn, event) {
  event.preventDefault();

  // Calculate sum of passengers and then write into field
  const passengerElements = document.querySelectorAll('.passengerThis'); 
  let sum = 0;
  for (const element of passengerElements) {
    const number = parseInt(element.textContent); 
    sum += number; 
  }
  // Write sum into the original field
  closeBtn.parentNode.parentNode.parentNode.previousElementSibling.childNodes[1].childNodes[1].nextElementSibling.childNodes[0].innerText = sum;

  const dateDropdownMobile = closeBtn.closest(".seats-dropdown-mobile");
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });

  dateDropdownMobile
    .querySelector(".seats-dropdown-mobile-inner")
    .classList.remove("showUp4");

  setTimeout(() => {
    dateDropdownMobile.classList.remove("show");
    dateDropdownMobile.remove();
  }, 500);
}

// ////////////////    SEATS STYLE POPUP MOBILE   /////////////////////////////

function openMobileStyleManager(field) {
  if (window.innerWidth > window.innerHeight) {
    return;
  }

  field.blur();
  const mobileView3 = `
    <div class="styles-dropdown-mobile">
        <div class="styles-dropdown-mobile-inner">
            <div class="sec21-mob">
                <h4 class="p-2">Choose Seat Class:</h4>
                <button class="cancel-styles-btn" type="none" onclick="closeMobileStyles(this, event)">X</button>
            </div>
            <div class="sec22-mob-3">
              <div class="popupSeatOptions">
                <div class="justify-content-around m-1" onclick="">
                  <div class="btn-group-vertical w-100" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="Economy" autocomplete="off" checked>
                    <label class="btn btn-outline-dark" for="Economy">Economy</label>
                  
                    <input type="radio" class="btn-check" name="btnradio" id="Premium Economy" autocomplete="off">
                    <label class="btn btn-outline-dark" for="Premium Economy">Premium Economy</label>
                  
                    <input type="radio" class="btn-check" name="btnradio" id="Business" autocomplete="off">
                    <label class="btn btn-outline-dark" for="Business">Business</label>
                  
                    <input type="radio" class="btn-check" name="btnradio" id="First" autocomplete="off">
                    <label class="btn btn-outline-dark" for="First">First</label>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div>
  `;
  // romeCalInitializer()

  field.insertAdjacentHTML("afterend", mobileView3);

  setTimeout(() => {
    document.querySelector(".styles-dropdown-mobile").classList.add("showUp5");
  }, 0);

  setTimeout(() => {
    document
      .querySelector(".styles-dropdown-mobile-inner")
      .classList.add("showUp4");
  }, 0);
}

// Function for the close button in date popup
function closeMobileStyles(closeBtn, event) {
  event.preventDefault();

  // Get all the radio buttons with the name "btnradio"
  const radioButtons = document.querySelectorAll('input[name="btnradio"]');

  // Find the checked radio button
  let checkedRadioButton;
  for (const radioButton of radioButtons) {
    if (radioButton.checked) {
      checkedRadioButton = radioButton;
      break;
    }
  }
  closeBtn.parentNode.parentNode.parentNode.previousElementSibling.childNodes[1].childNodes[1].nextElementSibling.innerText =
    checkedRadioButton.id;
  // console.log(closeBtn.parentNode.parentNode.parentNode.previousElementSibling.childNodes[1].childNodes[1].nextElementSibling.innerText)

  const dateDropdownMobile = closeBtn.closest(".styles-dropdown-mobile");
  const inputs = document.querySelectorAll("input");
  inputs.forEach((input) => {
    input.blur();
  });

  dateDropdownMobile
    .querySelector(".styles-dropdown-mobile-inner")
    .classList.remove("showUp4");

  setTimeout(() => {
    dateDropdownMobile.classList.remove("show");
    dateDropdownMobile.remove();
  }, 500);
}

// ////////////////    SEATS MANAGEMENT POPUP DESKTOP   /////////////////////////////

function openSeatsManager(field) {
  if (window.innerWidth < window.innerHeight) {
    return;
  }

  const seatsView = `
      <div class="seats-dropdown-desktop shadow" onmouseleave="mouseleaveSeats(this)">
        <div class="d-flex justify-content-between m-1">
            <label for="adults">
                Adults:
            </label>
            <div class="numberControls d-flex">
                <button onclick="passengerInc(event, 6)">+</button>
                <p class="passengerThis">1</p>
                <button onclick="passengerDec(event, 1)">-</button>
            </div>
        </div>
        <div class="d-flex justify-content-between m-1">
            <label for="adults">
                Children:
            </label>
            <div class="numberControls d-flex">
                <button onclick="passengerInc(event, 5)">+</button>
                <p class="passengerThis">0</p>
                <button onclick="passengerDec(event, 0)">-</button>
            </div>
        </div>
        <div class="d-flex justify-content-between m-1">
            <label for="adults">
                Infants:
            </label>
            <div class="numberControls d-flex">
                <button onclick="passengerInc(event, 2)">+</button>
                <p class="passengerThis">0</p>
                <button onclick="passengerDec(event, 0)">-</button>
            </div>
        </div>
      </div>
  `;
  // romeCalInitializer()

  field.insertAdjacentHTML("afterend", seatsView);

  // console.log("Opening on desktop");
}

// ////////////////    SEATS NUMBER MANAGEMENT   /////////////////////////////

function passengerInc(event, max) {
  event.preventDefault();
  const currentP = event.target.nextElementSibling;

  if (parseInt(currentP.innerHTML) < max) {
    currentP.innerText = parseInt(currentP.innerHTML) + 1;
  }

  // let originalF = currentP.parentNode.parentNode.parentNode.previousElementSibling.childNodes[1].childNodes[1].nextElementSibling.childNodes[0];
  // originalF.innerText = currentP.innerText;
}
function passengerDec(event, min) {
  event.preventDefault();
  const currentP = event.target.previousElementSibling;

  if (parseInt(currentP.innerHTML) > min) {
    currentP.innerText = parseInt(currentP.innerHTML) - 1;
  }
}
function mouseleaveSeats(element) {
  let originalF =
    element.previousElementSibling.childNodes[1].childNodes[1]
      .nextElementSibling.childNodes[0];

  var passengerThisElements = element.getElementsByClassName("passengerThis");
  var sum = 0;
  for (var i = 0; i < passengerThisElements.length; i++) {
    sum += parseInt(passengerThisElements[i].textContent);
  }

  originalF.innerText = sum;

  element.remove();
}

// Close dropdowns when user clicks outside of them
// document.addEventListener("click", event => {
//   const dropdowns = document.querySelectorAll(".optionsInputs");
//   dropdowns.forEach(dropdown => {
//     if (!dropdown.contains(document.querySelector(".seatsDownContainer"))) {
//       dropdown.innerHTML = "";
//     }
//   });
// });

function openStyleManager(field) {
  if (window.innerWidth < window.innerHeight) {
    return;
  }

  const stylesView = `
      <div class="style-dropdown-desktop shadow" onmouseleave="mouseleaveStyle(this)">
        <div class="d-flex justify-content-around m-1" onclick="selectedSeatStyle(event)">
          Economy
        </div>
        <div class="d-flex justify-content-around m-1" onclick="selectedSeatStyle(event)">
          Premium Economy
        </div>
        <div class="d-flex justify-content-around m-1" onclick="selectedSeatStyle(event)">
          Business
        </div>
        <div class="d-flex justify-content-around m-1" onclick="selectedSeatStyle(event)">
          First
        </div>
      </div>
  `;
  // romeCalInitializer()

  field.insertAdjacentHTML("afterend", stylesView);
}

// ////////////////    SEATS Style MANAGEMENT   /////////////////////////////

function selectedSeatStyle(event) {
  event.preventDefault();
  const currentStyle = event.target.innerText;

  let originalF2 =
    event.target.parentNode.previousElementSibling.childNodes[1].childNodes[1]
      .nextElementSibling;

  originalF2.innerHTML = "" + currentStyle;
  // console.log(originalF2);

  mouseleaveStyle(event.target.parentNode);
}
function mouseleaveStyle(element) {
  element.remove();
}
